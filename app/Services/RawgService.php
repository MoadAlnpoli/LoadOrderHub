<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RawgService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('RAWG_API_KEY', '');
    }

    /**
     * Search for a game and return its details & a list of common modding versions.
     * Priority: RAWG API -> Steam Store API (free) -> Curated database -> Generic fallback
     */
    public function getGameDetails(string $gameName): array
    {
        $slug = str()->slug($gameName);

        // Curated versions for popular games
        $popularGameVersions = [
            'skyrim-special-edition' => ['1.5.97', '1.6.353', '1.6.640', '1.6.1130', '1.6.1170'],
            'skyrim' => ['1.5.97', '1.6.353', '1.6.640', '1.6.1130', '1.6.1170'],
            'mount-and-blade-ii-bannerlord' => ['1.1.0', '1.2.0', '1.2.7', '1.2.8', '1.2.9', '1.2.10'],
            'cyberpunk-2077' => ['1.6', '2.0', '2.02', '2.1', '2.11', '2.12'],
            'fallout-4' => ['1.10.163', '1.10.980', '1.10.984'],
            'witcher-3' => ['1.32', '4.0', '4.04'],
            'minecraft' => ['1.20.1', '1.20.4', '1.21.0', '1.21.1'],
            'stardew-valley' => ['1.5.6', '1.6.0', '1.6.8'],
        ];

        $versions = $popularGameVersions[$slug] ?? ['1.0.0', '1.1.0', '1.2.0', '1.3.0'];

        // Try RAWG API first if key is available
        if (!empty($this->apiKey)) {
            try {
                $response = Http::get('https://api.rawg.io/api/games', [
                    'search' => $gameName,
                    'key' => $this->apiKey,
                    'page_size' => 1,
                ]);

                if ($response->successful() && !empty($response->json()['results'])) {
                    $gameData = $response->json()['results'][0];

                    $detailResponse = Http::get("https://api.rawg.io/api/games/{$gameData['id']}", [
                        'key' => $this->apiKey,
                    ]);

                    $description = strip_tags($gameData['name'] . ' is a popular video game.');
                    if ($detailResponse->successful()) {
                        $description = strip_tags($detailResponse->json()['description'] ?? $description);
                    }

                    $coverUrl = $gameData['background_image'] ?? null;
                    $localCover = $coverUrl ? $this->downloadImage($coverUrl, $gameData['slug']) : null;

                    return [
                        'name' => $gameData['name'],
                        'slug' => $gameData['slug'],
                        'description' => $description,
                        'cover' => $localCover ?? $coverUrl ?? $this->generatePlaceholder($gameData['name']),
                        'versions' => $versions,
                    ];
                }
            } catch (\Exception $e) {
                Log::error('RAWG API Exception: ' . $e->getMessage());
            }
        }

        // Try Steam Store API (free, no key needed)
        $steamResult = $this->searchSteam($gameName);
        if ($steamResult) {
            $localCover = $this->downloadImage($steamResult['cover'], $slug);
            return [
                'name' => $steamResult['name'],
                'slug' => str()->slug($steamResult['name']),
                'description' => $steamResult['description'],
                'cover' => $localCover ?? $steamResult['cover'],
                'versions' => $versions,
            ];
        }

        // Fallback to curated database with local image download
        return $this->getCuratedDetails($gameName, $versions);
    }

    /**
     * Search Steam Store API for a game (free, no key required).
     */
    protected function searchSteam(string $gameName): ?array
    {
        try {
            // Steam search endpoint (no API key needed)
            $response = Http::timeout(10)->get('https://store.steampowered.com/api/storesearch/', [
                'term' => $gameName,
                'l' => 'english',
                'cc' => 'US',
            ]);

            if ($response->successful() && !empty($response->json()['items'])) {
                $item = $response->json()['items'][0];
                $appId = $item['id'];
                $name = $item['name'];

                // Get game details
                $detailResponse = Http::timeout(10)->get("https://store.steampowered.com/api/appdetails", [
                    'appids' => $appId,
                    'l' => 'english',
                ]);

                $description = "{$name} is a popular video game.";
                if ($detailResponse->successful()) {
                    $detailData = $detailResponse->json()[$appId]['data'] ?? null;
                    if ($detailData) {
                        $description = strip_tags($detailData['short_description'] ?? $description);
                    }
                }

                // Steam header image is always reliable
                $cover = "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appId}/header.jpg";

                return [
                    'name' => $name,
                    'description' => $description,
                    'cover' => $cover,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Steam Store API search failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Download an image from URL and save it locally in public/images/games/.
     * Returns the public URL path, or null on failure.
     */
    protected function downloadImage(string $url, string $slug): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'image/jpeg';
                $ext = 'jpg';
                if (str_contains($contentType, 'png')) {
                    $ext = 'png';
                } elseif (str_contains($contentType, 'webp')) {
                    $ext = 'webp';
                } elseif (str_contains($contentType, 'gif')) {
                    $ext = 'gif';
                }

                $filename = $slug . '.' . $ext;
                $dir = public_path('images/games');

                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                file_put_contents("{$dir}/{$filename}", $response->body());

                return "/images/games/{$filename}";
            }
        } catch (\Exception $e) {
            Log::warning("Failed to download game image: {$e->getMessage()}");
        }

        return null;
    }

    /**
     * Generate a simple SVG placeholder with the game name as a data URI.
     */
    protected function generatePlaceholder(string $gameName): string
    {
        $initials = collect(explode(' ', $gameName))
            ->take(2)
            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->join('');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="460" height="215" viewBox="0 0 460 215">'
            . '<defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">'
            . '<stop offset="0%" style="stop-color:#6366f1"/>'
            . '<stop offset="100%" style="stop-color:#8b5cf6"/>'
            . '</linearGradient></defs>'
            . '<rect width="460" height="215" fill="url(#g)" rx="12"/>'
            . '<text x="230" y="115" font-family="Arial,sans-serif" font-size="64" font-weight="bold" fill="white" text-anchor="middle" dominant-baseline="central">'
            . htmlspecialchars($initials)
            . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Curated database of known games with reliable Steam CDN image URLs.
     */
    protected function getCuratedDetails(string $gameName, array $versions): array
    {
        $slug = str()->slug($gameName);

        // Comprehensive curated database with Steam App IDs
        $curatedGames = [
            'skyrim' => [
                'name' => 'Skyrim Special Edition',
                'appId' => 489830,
                'description' => 'The Elder Scrolls V: Skyrim Special Edition is an open-world action role-playing video game developed by Bethesda Game Studios.',
            ],
            'skyrim-special-edition' => [
                'name' => 'Skyrim Special Edition',
                'appId' => 489830,
                'description' => 'The Elder Scrolls V: Skyrim Special Edition is an open-world action role-playing video game developed by Bethesda Game Studios.',
            ],
            'bannerlord' => [
                'name' => 'Mount & Blade II: Bannerlord',
                'appId' => 261550,
                'description' => 'Mount & Blade II: Bannerlord is a strategy action role-playing game developed by TaleWorlds Entertainment.',
            ],
            'mount-and-blade-ii-bannerlord' => [
                'name' => 'Mount & Blade II: Bannerlord',
                'appId' => 261550,
                'description' => 'Mount & Blade II: Bannerlord is a strategy action role-playing game developed by TaleWorlds Entertainment.',
            ],
            'cyberpunk' => [
                'name' => 'Cyberpunk 2077',
                'appId' => 1091500,
                'description' => 'Cyberpunk 2077 is an action role-playing video game developed and published by CD Projekt Red.',
            ],
            'cyberpunk-2077' => [
                'name' => 'Cyberpunk 2077',
                'appId' => 1091500,
                'description' => 'Cyberpunk 2077 is an action role-playing video game developed and published by CD Projekt Red.',
            ],
            'minecraft' => [
                'name' => 'Minecraft',
                'appId' => null, // Not on Steam
                'cover' => 'https://www.minecraft.net/content/dam/games/minecraft/key-art/CC-702-702_key-art_600x360.jpg',
                'description' => 'Minecraft is a sandbox video game developed by Mojang Studios where players build and explore blocky worlds.',
            ],
            'fallout-4' => [
                'name' => 'Fallout 4',
                'appId' => 377160,
                'description' => 'Fallout 4 is an action role-playing game developed by Bethesda Game Studios set in a post-apocalyptic open world.',
            ],
            'witcher-3' => [
                'name' => 'The Witcher 3: Wild Hunt',
                'appId' => 292030,
                'description' => 'The Witcher 3: Wild Hunt is an action role-playing game developed by CD Projekt Red.',
            ],
            'the-witcher-3-wild-hunt' => [
                'name' => 'The Witcher 3: Wild Hunt',
                'appId' => 292030,
                'description' => 'The Witcher 3: Wild Hunt is an action role-playing game developed by CD Projekt Red.',
            ],
            'hearts-of-iron' => [
                'name' => 'Hearts of Iron IV',
                'appId' => 394360,
                'description' => 'Hearts of Iron IV is a grand strategy computer wargame developed by Paradox Development Studio.',
            ],
            'hearts-of-iron-iv' => [
                'name' => 'Hearts of Iron IV',
                'appId' => 394360,
                'description' => 'Hearts of Iron IV is a grand strategy computer wargame developed by Paradox Development Studio.',
            ],
            'stardew-valley' => [
                'name' => 'Stardew Valley',
                'appId' => 413150,
                'description' => 'Stardew Valley is a farming simulation game developed by ConcernedApe.',
            ],
            'elden-ring' => [
                'name' => 'Elden Ring',
                'appId' => 1245620,
                'description' => 'Elden Ring is an action role-playing game developed by FromSoftware and published by Bandai Namco.',
            ],
            'baldurs-gate-3' => [
                'name' => "Baldur's Gate 3",
                'appId' => 1086940,
                'description' => "Baldur's Gate 3 is a role-playing video game developed and published by Larian Studios.",
            ],
            'rimworld' => [
                'name' => 'RimWorld',
                'appId' => 294100,
                'description' => 'RimWorld is a top-down construction and management simulation developed by Ludeon Studios.',
            ],
        ];

        // Find matching curated game
        $matched = null;
        foreach ($curatedGames as $key => $data) {
            if ($slug === $key || str_contains($slug, $key)) {
                $matched = $data;
                break;
            }
        }

        if ($matched) {
            $coverUrl = $matched['cover'] ?? null;
            if (!$coverUrl && $matched['appId']) {
                $coverUrl = "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$matched['appId']}/header.jpg";
            }

            // Download and save locally
            $localCover = $coverUrl ? $this->downloadImage($coverUrl, $slug) : null;

            return [
                'name' => $matched['name'],
                'slug' => str()->slug($matched['name']),
                'description' => $matched['description'],
                'cover' => $localCover ?? $coverUrl ?? $this->generatePlaceholder($matched['name']),
                'versions' => $versions,
            ];
        }

        // Completely unknown game - try Steam search as last resort, or return placeholder
        $description = "A video game with rich gameplay features and a large modding community.";

        return [
            'name' => $gameName,
            'slug' => $slug,
            'description' => $description,
            'cover' => $this->generatePlaceholder($gameName),
            'versions' => $versions,
        ];
    }
}
