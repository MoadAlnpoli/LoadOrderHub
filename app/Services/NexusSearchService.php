<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NexusSearchService
{
    /**
     * Map database game slugs to Nexus Mods game slugs
     */
    public static function getRawNexusSlug(string $slug): string
    {
        $mappings = [
            'skyrim-special-edition' => 'skyrimspecialedition',
            'skyrim' => 'skyrim',
            'mount-blade-ii-bannerlord' => 'mountandblade2bannerlord',
            'bannerlord' => 'mountandblade2bannerlord',
            'minecraft-dungeons' => 'minecraftdungeons',
            'stardew-valley' => 'stardewvalley',
            'fallout-4' => 'fallout4',
            'cyberpunk-2077' => 'cyberpunk2077',
            'witcher-3' => 'witcher3',
            'minecraft' => 'minecraft',
        ];

        $slug = strtolower(trim($slug));
        if (isset($mappings[$slug])) {
            return $mappings[$slug];
        }

        return str_replace('-', '', $slug);
    }

    /**
     * Search Nexus Mods for a mod name under a game.
     * Uses DDG Lite proxied through Jina Reader to avoid Cloudflare/CAPTCHA blocking.
     */
    public static function searchMod(string $gameSlug, ?string $query): array
    {
        $nexusSlug = self::getRawNexusSlug($gameSlug);

        // If query is empty, crawl popular mods of the game from the main Nexus page
        if (empty($query)) {
            $url = "https://r.jina.ai/https://www.nexusmods.com/{$nexusSlug}/mods/";
            try {
                $response = Http::timeout(15)->get($url);
                if (!$response->successful()) {
                    return [];
                }
                $body = $response->body();
                $results = [];

                // Match relative or absolute markdown links, e.g. [SkyUI](/skyrimspecialedition/mods/12604) or [SkyUI](https://www.nexusmods.com/skyrimspecialedition/mods/12604)
                preg_match_all('/\[([^\]]+)\]\(((?:https:\/\/www\.nexusmods\.com)?\/' . $nexusSlug . '\/mods\/(\d+))[^\)]*\)/i', $body, $matches, PREG_SET_ORDER);

                foreach ($matches as $match) {
                    $title = trim($match[1]);
                    $pathOrUrl = $match[2];
                    $modId = $match[3];

                    // Skip generic helpers
                    if (in_array(strtolower($title), ['download', 'manual', 'nexus mods', 'mods', 'images', 'videos', 'articles', 'files', 'requirements', 'permissions', 'changelogs', 'log in', 'sign up'])) {
                        continue;
                    }
                    if (strlen($title) < 3 || str_contains(strtolower($title), 'cookies') || str_contains(strtolower($title), 'terms of service')) {
                        continue;
                    }

                    $targetUrl = str_starts_with($pathOrUrl, 'http') ? $pathOrUrl : "https://www.nexusmods.com" . $pathOrUrl;

                    $results[$modId] = [
                        'title' => $title,
                        'url' => $targetUrl,
                        'id' => $modId,
                        'game_slug' => $nexusSlug
                    ];
                }
                return array_values($results);
            } catch (\Exception $e) {
                Log::error("Error fetching popular mods: " . $e->getMessage());
                return [];
            }
        }

        $searchQuery = "site:nexusmods.com/{$nexusSlug} {$query}";
        
        // 1. Try direct DDG Lite search first (fastest, no Jina proxy limits/billing)
        try {
            $directUrl = "https://lite.duckduckgo.com/lite/";
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->asForm()
                ->post($directUrl, ['q' => $searchQuery]);

            if ($response->successful()) {
                $body = $response->body();
                $results = [];

                // Match links in DDG HTML: e.g. <a href="https://www.nexusmods.com/mountandblade2bannerlord/mods/123">Mod Name</a>
                // Or redirected links: <a href="https://duckduckgo.com/l/?uddg=https%3A%2F%2Fwww.nexusmods.com%2F...
                preg_match_all('/href="([^"]+)"[^>]*>([^<]+)<\/a>/i', $body, $matches, PREG_SET_ORDER);

                foreach ($matches as $match) {
                    $targetUrl = urldecode($match[1]);
                    $title = trim($match[2]);

                    // Extract actual URL if it goes through DDG redirect
                    if (str_contains($targetUrl, 'uddg=')) {
                        preg_match('/uddg=([^&]+)/', $targetUrl, $redirMatches);
                        if (!empty($redirMatches[1])) {
                            $targetUrl = urldecode($redirMatches[1]);
                        }
                    }

                    if (preg_match('/nexusmods\.com\/[a-zA-Z0-9_-]+\/mods\/(\d+)/i', $targetUrl, $urlMatches)) {
                        $modId = $urlMatches[1];
                        $cleanTitle = preg_replace('/\s+at\s+.*Nexus\s*-\s*Mods.*/i', '', $title);
                        $cleanTitle = preg_replace('/\s*-\s*Nexus\s+Mods/i', '', $cleanTitle);
                        $cleanTitle = trim($cleanTitle);

                        // Skip generic search tabs/links
                        if (in_array(strtolower($cleanTitle), ['images', 'videos', 'news', 'maps', 'next', 'prev'])) {
                            continue;
                        }

                        $results[$modId] = [
                            'title' => $cleanTitle,
                            'url' => $targetUrl,
                            'id' => $modId,
                            'game_slug' => $nexusSlug
                        ];
                    }
                }

                if (!empty($results)) {
                    return array_values($results);
                }
            }
        } catch (\Exception $e) {
            Log::warning("Direct DDG search failed, trying Jina proxy. Info: " . $e->getMessage());
        }

        // 2. Fallback to Jina Reader proxy if direct DDG request failed or returned empty
        $url = "https://r.jina.ai/https://lite.duckduckgo.com/lite/?q=" . urlencode($searchQuery);

        try {
            $response = Http::timeout(12)->get($url);
            if (!$response->successful()) {
                Log::error("DDG search failed for query: {$query}");
                return [];
            }

            $body = $response->body();
            $results = [];

            preg_match_all('/\[([^\]]+)\]\(https:\/\/duckduckgo\.com\/l\/\?uddg=([^&]+)/i', $body, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $title = trim($match[1]);
                $targetUrl = urldecode($match[2]);

                if (preg_match('/nexusmods\.com\/[a-zA-Z0-9_-]+\/mods\/(\d+)/i', $targetUrl, $urlMatches)) {
                    $modId = $urlMatches[1];

                    $cleanTitle = preg_replace('/\s+at\s+.*Nexus\s*-\s*Mods.*/i', '', $title);
                    $cleanTitle = preg_replace('/\s*-\s*Nexus\s+Mods/i', '', $cleanTitle);
                    $cleanTitle = trim($cleanTitle);

                    $results[] = [
                        'title' => $cleanTitle,
                        'url' => $targetUrl,
                        'id' => $modId,
                        'game_slug' => $nexusSlug
                    ];
                }
            }

            return array_values($results);
        } catch (\Exception $e) {
            Log::error("Error in NexusSearchService Jina Fallback: " . $e->getMessage());
            return [];
        }
    }

    public static function getModDetails(string $nexusUrl, array $knownVersions = []): array
    {
        $imageUrl = null;
        $description = null;
        $matchedVersions = [];
        $steamUrl = null;

        try {
            // Try direct fetch first to get og:image and og:description
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])->timeout(12)->get($nexusUrl);

            if ($response->successful()) {
                $html = $response->body();

                // Extract og:image
                if (preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $m)) {
                    $imageUrl = $m[1];
                }

                // Extract og:description
                if (preg_match('/<meta property="og:description" content="([^"]+)"/i', $html, $m)) {
                    $description = trim(html_entity_decode($m[1], ENT_QUOTES));
                    if (strlen($description) > 300) {
                        $description = substr($description, 0, 300) . '...';
                    }
                }

                // Match game versions
                foreach ($knownVersions as $versionStr) {
                    if (empty($versionStr)) continue;
                    // Check if version is mentioned in the HTML
                    if (str_contains($html, $versionStr)) {
                        $matchedVersions[] = $versionStr;
                    }
                }
                
                // Detect any Steam Workshop URLs
                if (preg_match('/(https:\/\/steamcommunity\.com\/(?:sharedfiles|workshop)\/filedetails\/\?id=\d+)/i', $html, $steamMatches)) {
                    $steamUrl = $steamMatches[1];
                }

                if ($imageUrl && $description) {
                    return [
                        'image_url' => $imageUrl,
                        'description' => $description,
                        'matched_versions' => array_unique($matchedVersions),
                        'steam_url' => $steamUrl
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Direct fetch failed for getModDetails {$nexusUrl}: " . $e->getMessage());
        }

        // Fallback to Jina Reader
        try {
            $url = "https://r.jina.ai/" . $nexusUrl;
            $response = Http::withHeaders([
                'X-Return-Format' => 'html'
            ])->timeout(15)->get($url);

            if ($response->successful()) {
                $html = $response->body();

                if (!$imageUrl && preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $m)) {
                    $imageUrl = $m[1];
                }
                if (!$imageUrl && preg_match('/<img[^>]+src="([^"]+(?:staticdelivery|nexusmods)[^"]+)"/i', $html, $m)) {
                    $imageUrl = $m[1];
                }

                if (!$description && preg_match('/<meta property="og:description" content="([^"]+)"/i', $html, $m)) {
                    $description = trim(html_entity_decode($m[1], ENT_QUOTES));
                    if (strlen($description) > 300) {
                        $description = substr($description, 0, 300) . '...';
                    }
                }

                foreach ($knownVersions as $versionStr) {
                    if (empty($versionStr)) continue;
                    if (str_contains($html, $versionStr)) {
                        $matchedVersions[] = $versionStr;
                    }
                }

                if (!$steamUrl && preg_match('/(https:\/\/steamcommunity\.com\/(?:sharedfiles|workshop)\/filedetails\/\?id=\d+)/i', $html, $steamMatches)) {
                    $steamUrl = $steamMatches[1];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jina Fallback failed for getModDetails {$nexusUrl}: " . $e->getMessage());
        }

        return [
            'image_url' => $imageUrl,
            'description' => $description,
            'matched_versions' => array_unique($matchedVersions),
            'steam_url' => $steamUrl
        ];
    }
}
