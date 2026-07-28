<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\YoutubeService;
use App\Services\AiProcessorService;
use App\Services\GameImageService;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\ModPack;
use App\Models\Mod;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScrapeModPacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modpacks:scrape {query?} {--limit=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes YouTube for mod load orders, extracts details using AI, and publishes them.';

    protected YoutubeService $youtube;
    protected AiProcessorService $ai;
    protected GameImageService $gameImageService;

    public function __construct(YoutubeService $youtube, AiProcessorService $ai, GameImageService $gameImageService)
    {
        parent::__construct();
        $this->youtube = $youtube;
        $this->ai = $ai;
        $this->gameImageService = $gameImageService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $query = $this->argument('query');
        $limit = (int) $this->option('limit');

        if (empty($query)) {
            $this->info("Zero-Touch Automation Mode: Scanning all games in database...");
            $games = Game::all();
            
            if ($games->isEmpty()) {
                $this->warn("No games in database to scan. Scanning default...");
                $this->scrapeForQuery("Bannerlord 1.2 mod load order", $limit);
                return Command::SUCCESS;
            }

            foreach ($games as $game) {
                $gameQuery = "{$game->name} mod load order";
                $this->info("Automatically scraping for: '{$gameQuery}'...");
                $this->scrapeForQuery($gameQuery, $limit);
            }
            return Command::SUCCESS;
        }

        $this->scrapeForQuery($query, $limit);
        return Command::SUCCESS;
    }

    /**
     * Perform the scraping and processing for a single query.
     */
    protected function scrapeForQuery(string $query, int $limit): void
    {
        $this->info("Searching YouTube for: '{$query}'...");
        $videos = $this->youtube->searchVideos($query, $limit);

        if (empty($videos)) {
            $this->warn("No videos found on YouTube for '{$query}'.");
            return;
        }

        $botUser = User::where('email', 'bot@modplatform.com')->first();
        if (!$botUser) {
            $botUser = User::create([
                'name' => 'Auto Bot',
                'email' => 'bot@modplatform.com',
                'password' => bcrypt(Str::random(16)),
                'is_admin' => true,
            ]);
        }

        foreach ($videos as $video) {
            $videoId = $video['video_id'];

            // Check if already processed
            if (ModPack::where('youtube_video_id', $videoId)->exists()) {
                $this->info("Video [{$videoId}] already processed. Skipping.");
                continue;
            }

            $this->info("Fetching details for video [{$videoId}]...");
            $details = $this->youtube->getVideoDetails($videoId);

            $this->info("Processing details via AI...");
            $extracted = $this->ai->processVideoData($details['title'], $details['description'], $details['transcript']);

            if (!$extracted || empty($extracted['mods'])) {
                $this->error("Failed to extract mod details from video [{$videoId}].");
                continue;
            }

            // 1. Deducing the Game
            $gameSlug = $this->deduceGameSlug($details['title'] . ' ' . $details['description']);
            $gameName = $this->deduceGameName($gameSlug);

            // Fetch high-quality real game cover art
            $gameCover = $this->gameImageService->getGameCover($gameName);

            $game = Game::firstOrCreate(
                ['slug' => $gameSlug],
                [
                    'name' => $gameName,
                    'description' => "Automated page for {$gameName} mod orders.",
                    'thumbnail' => $gameCover,
                ]
            );

            // 2. Resolve multiple Game Versions
            $versionIds = [];
            $versionsList = $extracted['game_versions'] ?? [$extracted['game_version'] ?? 'unknown'];
            foreach ($versionsList as $vStr) {
                $vStr = trim($vStr);
                if ($vStr === 'unknown' || empty($vStr)) {
                    $vStr = '1.0.0';
                }
                $gv = GameVersion::firstOrCreate([
                    'game_id' => $game->id,
                    'version' => $vStr,
                ]);
                $versionIds[] = $gv->id;
            }

            // 3. Save Thumbnail locally
            $localThumbnail = $this->downloadThumbnail($details['thumbnail_url'], $videoId);

            // 4. Create Mod Pack
            $modPack = ModPack::create([
                'title_en' => $extracted['title_en'],
                'title_ar' => $extracted['title_ar'],
                'description_en' => $extracted['description_en'],
                'description_ar' => $extracted['description_ar'],
                'youtube_video_id' => $videoId,
                'youtube_thumbnail_url' => $details['thumbnail_url'],
                'local_thumbnail_path' => $localThumbnail,
                'views_count' => 0,
                'upvotes' => 0,
                'downvotes' => 0,
                'is_published' => false,
                'created_by' => $botUser->id,
            ]);

            // Sync multiple game versions
            $modPack->gameVersions()->sync($versionIds);

            // 5. Create Mods in order
            foreach ($extracted['mods'] as $index => $m) {
                Mod::create([
                    'mod_pack_id' => $modPack->id,
                    'name' => $m['name'],
                    'description' => null,
                    'load_order' => $m['load_order'] ?? ($index + 1),
                    'nexus_url' => $m['nexus_url'] ?? null,
                    'download_url' => $m['download_url'] ?? $m['nexus_url'] ?? null,
                ]);
            }

            $this->info("Successfully created Mod Pack: '{$extracted['title_en']}' (ID: {$modPack->id})");
        }
    }

    protected function deduceGameSlug(string $text): string
    {
        $text = strtolower($text);
        if (str_contains($text, 'skyrim') || str_contains($text, 'elder scrolls')) {
            return 'skyrim-special-edition';
        }
        if (str_contains($text, 'bannerlord') || str_contains($text, 'mount') || str_contains($text, 'blade')) {
            return 'mount-and-blade-ii-bannerlord';
        }
        if (str_contains($text, 'cyberpunk') || str_contains($text, 'cyber punk')) {
            return 'cyberpunk-2077';
        }
        return 'other-game';
    }

    protected function deduceGameName(string $slug): string
    {
        return match ($slug) {
            'skyrim-special-edition' => 'Skyrim Special Edition',
            'mount-and-blade-ii-bannerlord' => 'Mount & Blade II: Bannerlord',
            'cyberpunk-2077' => 'Cyberpunk 2077',
            default => 'Other Game',
        };
    }

    protected function downloadThumbnail(string $url, string $videoId): ?string
    {
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $filename = "thumbnails/{$videoId}.jpg";
                Storage::disk('public')->put($filename, $response->body());
                return 'storage/' . $filename;
            }
        } catch (\Exception $e) {
            Log::error("Failed to download thumbnail for video {$videoId}: " . $e->getMessage());
        }
        return null;
    }
}
