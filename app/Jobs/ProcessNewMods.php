<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\YoutubeService;
use App\Services\AiProcessorService;
use App\Services\NexusSearchService;
use App\Models\VideoStaging;
use App\Models\Game;
use App\Models\Mod;
use App\Models\ModPack;
use App\Models\Category;
use App\Models\GameVersion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessNewMods implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Starting ProcessNewMods automated pipeline job.');

        // 1. Discover videos from channels
        $channels = [
            'Strat Gaming' => 'UCeS11lS404zF29JmQ-Q1s4g',
            'Strat Gaming Guides' => 'UCWXXAjBRaEpQdxKvDMexJBg',
            'Strat Plays Daily' => 'UC5u_H46L7C1n5K4G4Y1z-wQ',
        ];

        $youtube = new YoutubeService();
        $discoveredCount = 0;

        foreach ($channels as $channelName => $channelId) {
            try {
                // Get last 5 videos
                $videos = $youtube->getVideosFromChannel($channelId, 5);
                foreach ($videos as $v) {
                    $exists = VideoStaging::where('video_id', $v['video_id'])->exists();
                    if (!$exists) {
                        // Deduce game based on title/description keywords
                        $gameId = $this->deduceGameId($v['title'] . ' ' . $v['description']);

                        VideoStaging::create([
                            'video_id' => $v['video_id'],
                            'title' => $v['title'],
                            'description' => $v['description'],
                            'published_at' => !empty($v['published_at']) ? new \DateTime($v['published_at']) : now(),
                            'game_id' => $gameId,
                            'processed' => false,
                        ]);
                        $discoveredCount++;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error discovering videos for channel {$channelName}: " . $e->getMessage());
            }
        }

        Log::info("Discovered {$discoveredCount} new staged videos.");

        // 2. Process staged videos
        $stagedVideos = VideoStaging::where('processed', false)->get();
        $ai = new AiProcessorService();

        foreach ($stagedVideos as $video) {
            $transcriptFetched = false;
            $failureReason = null;
            $isValidJson = false;
            $totalModsExtracted = 0;
            $lowConfidenceCount = 0;

            try {
                Log::info("Processing staged video: {$video->title} ({$video->video_id})");

                // Get details (transcripts & descriptions)
                $details = $youtube->getVideoDetails($video->video_id);
                
                $transcriptFetched = $details['has_transcript'] ?? false;
                $failureReason = $details['transcript_failure_reason'] ?? null;
                $transcript = $details['transcript'] ?: $video->description;

                // Send to Gemini
                $extracted = $ai->processVideoData($video->title, $video->description, $transcript);

                if (empty($extracted) || empty($extracted['mods'])) {
                    Log::warning("No mods extracted for video {$video->video_id}");
                    $video->update(['processed' => true]);

                    // Save log
                    \App\Models\ExtractionLog::create([
                        'video_id' => $video->video_id,
                        'title' => $video->title,
                        'transcript_fetched' => $transcriptFetched,
                        'failure_reason' => $failureReason ?: 'Gemini returned empty or no mods.',
                        'is_valid_json' => !empty($extracted),
                        'total_mods_extracted' => 0,
                        'low_confidence_count' => 0,
                    ]);
                    continue;
                }

                $isValidJson = true;

                // If game_id is null on video staging, try to deduce it from extracted info or title
                $gameId = $video->game_id ?: $this->deduceGameId($video->title . ' ' . ($extracted['title_en'] ?? ''));
                if (!$gameId) {
                    // Default to Bannerlord if completely unknown
                    $bannerlord = Game::where('slug', 'like', '%bannerlord%')->first();
                    $gameId = $bannerlord ? $bannerlord->id : null;
                }

                if (!$gameId) {
                    Log::warning("Could not associate game with video {$video->video_id}, skipping.");
                    continue;
                }

                $game = Game::find($gameId);

                // Auto-create category if missing or match category
                $categorySlug = 'single-video-lets-plays';
                if (Str::contains(strtolower($video->title), ['guide', 'tutorial', 'how to'])) {
                    $categorySlug = 'guides';
                }
                $category = Category::where('slug', $categorySlug)->first();

                // Deducing version
                $versionStr = $extracted['game_version'] ?? 'unknown';
                $gameVersion = null;
                if ($versionStr !== 'unknown') {
                    $gameVersion = GameVersion::firstOrCreate([
                        'game_id' => $gameId,
                        'version' => $versionStr
                    ]);
                }

                // If no transcript was fetched, force confidence to low for all mods
                if (!$transcriptFetched) {
                    foreach ($extracted['mods'] as &$m) {
                        $m['confidence'] = 'low';
                    }
                    unset($m);
                }

                $totalModsExtracted = count($extracted['mods']);

                // Loop through mods and process them
                foreach ($extracted['mods'] as $index => $m) {
                    $modName = trim($m['name']);
                    if (empty($modName)) continue;

                    if (strtolower($m['confidence'] ?? '') === 'low') {
                        $lowConfidenceCount++;
                    }

                    // Deduplicate
                    $existingMod = Mod::where('game_id', $gameId)
                        ->where(function($q) use ($modName) {
                            $q->where('name', $modName)
                              ->orWhere('slug', Str::slug($modName));
                        })->first();

                    // Search links if missing
                    $nexusUrl = $m['nexus_url'] ?? null;
                    $steamUrl = $m['steam_url'] ?? null;

                    if (!$nexusUrl) {
                        $nexusUrl = $this->searchNexusLink($game->slug, $modName);
                    }
                    if (!$steamUrl) {
                        $steamUrl = $this->searchSteamLink($game->slug, $modName);
                    }

                    // Enrich details from Nexus if URL available
                    $imageUrl = null;
                    $description = 'Automated mod entry created from YouTube video pipeline.';
                    $matchedVersions = [];

                    if ($nexusUrl) {
                        $knownVersions = GameVersion::where('game_id', $gameId)->pluck('version')->toArray();
                        $details = NexusSearchService::getModDetails($nexusUrl, $knownVersions);
                        
                        $imageUrl = $details['image_url'] ?? null;
                        if (!empty($details['description'])) {
                            $description = $details['description'];
                        }
                        if (!empty($details['steam_url']) && empty($steamUrl)) {
                            $steamUrl = $details['steam_url'];
                        }
                        $matchedVersions = $details['matched_versions'] ?? [];
                    }

                    $downloadUrl = $m['download_url'] ?? $nexusUrl ?? $steamUrl ?? null;
                    $loadOrder = (int)($m['load_order'] ?? ($index + 1));

                    // Check confidence
                    $confidence = strtolower($m['confidence'] ?? 'high');
                    $issuesNote = null;
                    $hasIssues = false;
                    if ($confidence === 'low') {
                        $hasIssues = true;
                        $issuesNote = '⚠️ لم يتم التعرف تلقائياً على النسخة، يرجى اختيارها يدوياً';
                    }

                    $targetMod = null;

                    if ($existingMod) {
                        // Update missing fields
                        if (empty($existingMod->nexus_url) && $nexusUrl) {
                            $existingMod->nexus_url = $nexusUrl;
                        }
                        if (empty($existingMod->steam_url) && $steamUrl) {
                            $existingMod->steam_url = $steamUrl;
                        }
                        if (empty($existingMod->download_url) && $downloadUrl) {
                            $existingMod->download_url = $downloadUrl;
                        }
                        if (empty($existingMod->image_url) && $imageUrl) {
                            $existingMod->image_url = $imageUrl;
                        }
                        if (empty($existingMod->description) || $existingMod->description === 'Automated mod entry created from YouTube video pipeline.') {
                            if ($description !== 'Automated mod entry created from YouTube video pipeline.') {
                                $existingMod->description = $description;
                            }
                        }
                        if ($hasIssues && !$existingMod->has_issues) {
                            $existingMod->has_issues = true;
                            $existingMod->issues_note = $issuesNote;
                        }
                        $existingMod->save();
                        $targetMod = $existingMod;
                    } else {
                        // Create new mod
                        $newMod = Mod::create([
                            'game_id' => $gameId,
                            'category_id' => $category ? $category->id : null,
                            'name' => $modName,
                            'slug' => Str::slug($modName),
                            'description' => $description,
                            'load_order' => $loadOrder,
                            'nexus_url' => $nexusUrl,
                            'steam_url' => $steamUrl,
                            'download_url' => $downloadUrl,
                            'image_url' => $imageUrl,
                            'has_issues' => $hasIssues,
                            'issues_note' => $issuesNote,
                        ]);
                        $targetMod = $newMod;
                    }

                    // Sync game versions
                    if ($targetMod) {
                        $versionIdsToSync = [];
                        if ($gameVersion) {
                            $versionIdsToSync[] = $gameVersion->id;
                        }

                        if (!empty($matchedVersions)) {
                            $matchedVersionIds = GameVersion::where('game_id', $gameId)
                                ->whereIn('version', $matchedVersions)
                                ->pluck('id')
                                ->toArray();
                            
                            $versionIdsToSync = array_unique(array_merge($versionIdsToSync, $matchedVersionIds));
                        }

                        if (!empty($versionIdsToSync)) {
                            $targetMod->gameVersions()->syncWithoutDetaching($versionIdsToSync);
                        }
                    }
                }

                // Save log
                \App\Models\ExtractionLog::create([
                    'video_id' => $video->video_id,
                    'title' => $video->title,
                    'transcript_fetched' => $transcriptFetched,
                    'failure_reason' => $failureReason,
                    'is_valid_json' => $isValidJson,
                    'total_mods_extracted' => $totalModsExtracted,
                    'low_confidence_count' => $lowConfidenceCount,
                ]);

                // Mark video as processed
                $video->update(['processed' => true]);
                Log::info("Successfully processed video {$video->video_id}");

            } catch (\Exception $ex) {
                Log::error("Error processing staged video {$video->video_id}: " . $ex->getMessage());

                // Save log
                \App\Models\ExtractionLog::create([
                    'video_id' => $video->video_id,
                    'title' => $video->title,
                    'transcript_fetched' => $transcriptFetched,
                    'failure_reason' => $failureReason ?: $ex->getMessage(),
                    'is_valid_json' => $isValidJson,
                    'total_mods_extracted' => $totalModsExtracted,
                    'low_confidence_count' => $lowConfidenceCount,
                ]);
            }
        }
    }

    private function deduceGameId(string $text): ?int
    {
        $text = strtolower($text);
        if (str_contains($text, 'skyrim')) {
            $g = Game::where('slug', 'like', '%skyrim%')->first();
            if ($g) return $g->id;
        }
        if (str_contains($text, 'bannerlord') || str_contains($text, 'mount & blade') || str_contains($text, 'mountandblade')) {
            $g = Game::where('slug', 'like', '%bannerlord%')->first();
            if ($g) return $g->id;
        }
        if (str_contains($text, 'cyberpunk')) {
            $g = Game::where('slug', 'like', '%cyberpunk%')->first();
            if ($g) return $g->id;
        }
        if (str_contains($text, 'minecraft')) {
            $g = Game::where('slug', 'like', '%minecraft%')->first();
            if ($g) return $g->id;
        }
        return null;
    }

    private function searchNexusLink(string $gameSlug, string $modName): ?string
    {
        try {
            $results = NexusSearchService::searchMod($gameSlug, $modName);
            if (!empty($results)) {
                $match = $results[0];
                // Apply Fuzzy Matching
                $cleanModName = strtolower(preg_replace('/[^a-z0-9]/', '', $modName));
                $cleanMatchTitle = strtolower(preg_replace('/[^a-z0-9]/', '', $match['title']));
                
                similar_text($cleanModName, $cleanMatchTitle, $percent);
                
                // If one contains the other entirely, it's a very strong match
                if (str_contains($cleanMatchTitle, $cleanModName) || str_contains($cleanModName, $cleanMatchTitle)) {
                    $percent += 20;
                }

                if ($percent >= 45) { // Lowered threshold from 70
                    return $match['url'];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Nexus Auto-link failed for {$modName}: " . $e->getMessage());
        }
        return null;
    }

    private function searchSteamLink(string $gameSlug, string $modName): ?string
    {
        $appIdMap = [
            'skyrim-special-edition' => 489830,
            'skyrim' => 72850,
            'mount-blade-ii-bannerlord' => 261550,
            'bannerlord' => 261550,
            'cyberpunk-2077' => 1091500,
        ];

        $appId = $appIdMap[$gameSlug] ?? null;
        if (!$appId) return null;

        $steamKey = env('STEAM_KEY') ?: env('STEAM_API_KEY');

        // 1. Try querying Steam Web API
        if (!empty($steamKey)) {
            try {
                $url = "https://api.steampowered.com/IPublishedFileService/QueryFiles/v1/";
                $response = Http::timeout(8)->get($url, [
                    'key' => $steamKey,
                    'appid' => $appId,
                    'search_text' => $modName,
                    'numperpage' => 3,
                ]);

                if ($response->successful()) {
                    $details = $response->json()['response']['publishedfiledetails'] ?? [];
                    foreach ($details as $detail) {
                        $title = $detail['title'] ?? '';
                        similar_text(strtolower($modName), strtolower($title), $percent);
                        if ($percent >= 70) {
                            return "https://steamcommunity.com/sharedfiles/filedetails/?id=" . $detail['publishedfileid'];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Steam Web API Auto-link failed for {$modName}: " . $e->getMessage());
            }
        }

        // 2. Fallback to DDG search for Steam Workshop link
        try {
            $query = "site:steamcommunity.com/sharedfiles/filedetails/ appid={$appId} {$modName}";
            $response = Http::timeout(6)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'])
                ->get("https://lite.duckduckgo.com/lite/", ['q' => $query]);

            if ($response->successful()) {
                $body = urldecode($response->body());
                preg_match_all('/https?:\/\/steamcommunity\.com\/sharedfiles\/filedetails\/\?id=(\d+)/i', $body, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $fileId = $match[1];
                    // Since DDG doesn't give us clean title of the workshop page easily,
                    // we'll verify it by matching the link. The first one is the most relevant.
                    return "https://steamcommunity.com/sharedfiles/filedetails/?id=" . $fileId;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Steam DDG Fallback Auto-link failed for {$modName}: " . $e->getMessage());
        }

        return null;
    }
}
