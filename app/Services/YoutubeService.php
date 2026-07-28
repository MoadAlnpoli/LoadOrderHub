<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YoutubeService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.key', env('YOUTUBE_API_KEY', ''));
    }

    /**
     * Search YouTube for videos matching a query
     */
    public function searchVideos(string $query, int $maxResults = 5, string $publishedAfter = null): array
    {
        if (empty($this->apiKey)) {
            Log::warning('YouTube API key is missing. Returning mock search results.');
            return $this->getMockSearchResults($query);
        }

        try {
            $params = [
                'part' => 'snippet',
                'q' => $query,
                'maxResults' => $maxResults,
                'type' => 'video',
                'key' => $this->apiKey,
            ];

            if ($publishedAfter) {
                $params['publishedAfter'] = $publishedAfter;
            }

            $response = Http::get('https://www.googleapis.com/youtube/v3/search', $params);

            if ($response->successful()) {
                $items = $response->json()['items'] ?? [];
                $videos = [];
                foreach ($items as $item) {
                    $videos[] = [
                        'video_id' => $item['id']['videoId'],
                        'title' => $item['snippet']['title'],
                        'description' => $item['snippet']['description'],
                        'thumbnail_url' => $item['snippet']['thumbnails']['high']['url'] ?? $item['snippet']['thumbnails']['default']['url'],
                    ];
                }
                return $videos;
            }

            Log::error('YouTube search request failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('YouTube search exception: ' . $e->getMessage());
        }

        return $this->getMockSearchResults($query);
    }

    /**
     * Search YouTube for videos uploaded by a specific channel ID
     */
    public function getVideosFromChannel(string $channelId, int $maxResults = 5, string $publishedAfter = null): array
    {
        if (empty($this->apiKey)) {
            Log::warning('YouTube API key is missing. Returning mock channel videos.');
            $mockResults = $this->getMockSearchResults("channel-{$channelId}");
            foreach ($mockResults as &$res) {
                $res['published_at'] = now()->toRfc3339String();
            }
            return $mockResults;
        }

        try {
            $params = [
                'part' => 'snippet',
                'channelId' => $channelId,
                'maxResults' => $maxResults,
                'order' => 'date',
                'type' => 'video',
                'key' => $this->apiKey,
            ];

            if ($publishedAfter) {
                $params['publishedAfter'] = $publishedAfter;
            }

            $response = Http::get('https://www.googleapis.com/youtube/v3/search', $params);

            if ($response->successful()) {
                $items = $response->json()['items'] ?? [];
                $videos = [];
                foreach ($items as $item) {
                    $videos[] = [
                        'video_id' => $item['id']['videoId'],
                        'title' => $item['snippet']['title'],
                        'description' => $item['snippet']['description'],
                        'published_at' => $item['snippet']['publishedAt'] ?? null,
                        'thumbnail_url' => $item['snippet']['thumbnails']['high']['url'] ?? $item['snippet']['thumbnails']['default']['url'],
                    ];
                }
                return $videos;
            }

            Log::error('YouTube channel search request failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('YouTube channel search exception: ' . $e->getMessage());
        }

        $mockResults = $this->getMockSearchResults("channel-{$channelId}");
        foreach ($mockResults as &$res) {
            $res['published_at'] = now()->toRfc3339String();
        }
        return $mockResults;
    }

    /**
     * Retrieve transcripts or descriptions for a specific video
     */
    public function getVideoDetails(string $videoId): array
    {
        if (empty($this->apiKey)) {
            $mock = $this->getMockVideoDetails($videoId);
            $mock['has_transcript'] = true;
            $mock['transcript_failure_reason'] = null;
            return $mock;
        }

        try {
            // Get description first
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'part' => 'snippet',
                'id' => $videoId,
                'key' => $this->apiKey,
            ]);

            $description = '';
            $title = '';
            $thumbnail = '';

            if ($response->successful()) {
                $items = $response->json()['items'] ?? [];
                if (!empty($items)) {
                    $snippet = $items[0]['snippet'];
                    $title = $snippet['title'];
                    $description = $snippet['description'];
                    $thumbnail = $snippet['thumbnails']['high']['url'] ?? $snippet['thumbnails']['default']['url'];
                }
            }

            // Attempt to get transcript
            $transcript = $this->fetchTranscript($videoId);
            $hasTranscript = !empty($transcript);

            return [
                'video_id' => $videoId,
                'title' => $title,
                'description' => $description,
                'transcript' => $transcript ?: $description, // Use description if transcript is empty
                'thumbnail_url' => $thumbnail,
                'has_transcript' => $hasTranscript,
                'transcript_failure_reason' => $hasTranscript ? null : 'No caption tracks found or HTTP connection failed.',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching video details: ' . $e->getMessage());
            return [
                'video_id' => $videoId,
                'title' => '',
                'description' => '',
                'transcript' => '',
                'thumbnail_url' => '',
                'has_transcript' => false,
                'transcript_failure_reason' => $e->getMessage(),
            ];
        }
    }

    /**
     * Try to fetch transcript for video.
     * Note: YouTube caption scraping is rate-limited or requires specific libraries.
     * This method attempts a basic fetch or fallback.
     */
    protected function fetchTranscript(string $videoId): string
    {
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get("https://www.youtube.com/watch?v=" . urlencode($videoId));

            if (!$response->successful()) {
                return '';
            }

            $body = $response->body();

            // Locate captionTracks JSON in player response payload
            if (preg_match('/"captionTracks":\s*\[(.*?)\]/', $body, $matches)) {
                $captionTracksJson = '[' . $matches[1] . ']';
                $captionTracks = json_decode($captionTracksJson, true);

                if (!empty($captionTracks)) {
                    $selectedTrack = null;

                    // Pass 1: Prefer manual English subtitle track
                    foreach ($captionTracks as $track) {
                        $langCode = strtolower($track['languageCode'] ?? '');
                        $kind = strtolower($track['kind'] ?? '');
                        if (str_starts_with($langCode, 'en') && $kind !== 'asr') {
                            $selectedTrack = $track;
                            break;
                        }
                    }

                    // Pass 2: Fallback to auto-generated English subtitle track
                    if (!$selectedTrack) {
                        foreach ($captionTracks as $track) {
                            $langCode = strtolower($track['languageCode'] ?? '');
                            if (str_starts_with($langCode, 'en')) {
                                $selectedTrack = $track;
                                break;
                            }
                        }
                    }

                    // Pass 3: Fallback to the first track in array
                    if (!$selectedTrack) {
                        $selectedTrack = $captionTracks[0];
                    }

                    if (!empty($selectedTrack['baseUrl'])) {
                        $xmlResponse = Http::timeout(8)->get($selectedTrack['baseUrl']);
                        if ($xmlResponse->successful()) {
                            $xmlContent = $xmlResponse->body();
                            // Parse timedtext xml: <text start="0" dur="1">Text Here</text>
                            preg_match_all('/<text[^>]*>([^<]*)<\/text>/is', $xmlContent, $textMatches);
                            if (!empty($textMatches[1])) {
                                $transcript = '';
                                foreach ($textMatches[1] as $t) {
                                    $transcript .= html_entity_decode(trim($t)) . ' ';
                                }
                                return trim($transcript);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("Error fetching subtitle transcript for video {$videoId}: " . $e->getMessage());
        }

        return '';
    }

    /**
     * Mock data for testing when API key is missing
     */
    protected function getMockSearchResults(string $query): array
    {
        if (str_contains(strtolower($query), 'skyrim')) {
            return [
                [
                    'video_id' => 'dQw4w9WgXcQ',
                    'title' => 'Skyrim Anniversary Edition Mod Load Order Guide - Stable & Beautiful 2026',
                    'description' => 'Today we look at a fully stable load order for Skyrim AE version 1.6.640 featuring over 15 mods including SkyUI, SMIM, Lux, and realistic water overhauls.',
                    'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                ]
            ];
        }

        return [
            [
                'video_id' => '8bT_pC4j5J8',
                'title' => 'Mount & Blade II: Bannerlord 1.2.9 Realism Modlist & Battle Order',
                'description' => 'A perfect modlist for Bannerlord 1.2.9 featuring Realistic Battle Mod, Harmony, ButterLib, Diplomacy, and more.',
                'thumbnail_url' => 'https://img.youtube.com/vi/8bT_pC4j5J8/maxresdefault.jpg',
            ]
        ];
    }

    /**
     * Mock video details
     */
    protected function getMockVideoDetails(string $videoId): array
    {
        if ($videoId === 'dQw4w9WgXcQ') {
            return [
                'video_id' => 'dQw4w9WgXcQ',
                'title' => 'Skyrim Anniversary Edition Mod Load Order Guide - Stable & Beautiful 2026',
                'description' => "Mod list details:\n1. Address Library for SKSE Plugins\n2. SkyUI\n3. Static Mesh Improvement Mod\n4. Noble Skyrim HD\n5. Skyrim Flora Overhaul",
                'transcript' => "Welcome guys! Today we are showing the ultimate stable mod load order for Skyrim AE 1.6.640. First, make sure you download the Address Library for SKSE Plugins. Next is SkyUI. Then install Static Mesh Improvement Mod (SMIM), Noble Skyrim HD-2K, and Skyrim Flora Overhaul. Make sure to order them exactly like this to prevent crashes.",
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
            ];
        }

        return [
            'video_id' => $videoId,
            'title' => 'Mount & Blade II: Bannerlord 1.2.9 Realism Modlist & Battle Order',
            'description' => "Load Order:\n1. Harmony\n2. ButterLib\n3. UIExtenderEx\n4. Mod Configuration Menu\n5. Realistic Battle Mod",
            'transcript' => "Hello everyone. For Mount & Blade 2 Bannerlord version 1.2.9, here is the load order. You must put Harmony at the top, then ButterLib, then UIExtenderEx, followed by Mod Configuration Menu. After that, load the Realistic Battle Mod. This ensures complete stability.",
            'thumbnail_url' => 'https://img.youtube.com/vi/8bT_pC4j5J8/maxresdefault.jpg',
        ];
    }
}
