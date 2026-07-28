<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NexusModsService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.nexusmods.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.nexus.api_key', env('NEXUS_API_KEY', ''));
    }

    protected function headers(): array
    {
        return [
            'apiKey'       => $this->apiKey,
            'Accept'       => 'application/json',
            'User-Agent'   => 'LoadOrderHub/1.0',
        ];
    }

    /**
     * Fetch a single mod's details by Nexus game domain and mod id.
     */
    public function fetchMod(string $gameDomain, int $modId): ?array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/games/{$gameDomain}/mods/{$modId}.json");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("NexusMods API: failed to fetch mod {$modId} for {$gameDomain}", [
                'status' => $response->status(),
            ]);
        } catch (\Exception $e) {
            Log::error('NexusMods API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch a list of images for a mod.
     */
    public function fetchModImages(string $gameDomain, int $modId): array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/games/{$gameDomain}/mods/{$modId}/images.json");

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Exception $e) {
            Log::error('NexusMods fetchModImages error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Fetch files info (to detect compatible game versions).
     */
    public function fetchModFiles(string $gameDomain, int $modId): array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/games/{$gameDomain}/mods/{$modId}/files.json");

            if ($response->successful()) {
                return $response->json()['files'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error('NexusMods fetchModFiles error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Detect the compatible game version from file names.
     * Compares Nexus file names against the game's known versions in the DB.
     *
     * @param  array  $files   Result of fetchModFiles()
     * @param  \App\Models\Game  $game
     * @return array  List of matching GameVersion IDs
     */
    public function detectCompatibleVersions(array $files, \App\Models\Game $game): array
    {
        $knownVersions = $game->versions()->get();
        $matchedIds    = [];

        foreach ($files as $file) {
            $fileName = strtolower($file['file_name'] ?? '');
            foreach ($knownVersions as $gv) {
                $ver = strtolower(str_replace('.', '', $gv->version));
                if (str_contains($fileName, $ver) || str_contains($fileName, $gv->version)) {
                    $matchedIds[] = $gv->id;
                }
            }
        }

        return array_unique($matchedIds);
    }

    /**
     * Extract simple tags from a mod description.
     */
    public function extractTags(string $description, array $existingCategories = []): array
    {
        $text  = strtolower(strip_tags($description));
        $known = ['graphics', 'gameplay', 'texture', 'weather', 'lighting', 'audio',
                  'ui', 'interface', 'bug fix', 'armor', 'weapon', 'quest', 'follower',
                  'settlement', 'performance', 'animation', 'npc', 'city', 'overhaul'];

        $found = [];
        foreach ($known as $kw) {
            if (str_contains($text, $kw)) {
                $found[] = ucwords($kw);
            }
        }

        return array_unique(array_merge($found, $existingCategories));
    }

    /**
     * Parse FPS impact from a mod description.
     * Returns a string like "+5–10 FPS" or "Minimal" or null.
     */
    public function parseFpsImpact(string $description): ?string
    {
        if (preg_match('/[-+]?\d+[\-–]\d+\s*fps/i', $description, $m)) {
            return strtoupper(trim($m[0]));
        }
        if (str_contains(strtolower($description), 'performance friendly')) {
            return 'Minimal';
        }
        return null;
    }

    /**
     * Download and store a mod image locally.
     * Returns the stored relative path or null on failure.
     */
    public function downloadImage(string $url, string $slug): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);
            if (!$response->successful()) return null;

            $ext       = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename  = "mods/{$slug}." . $ext;
            $storagePath = storage_path("app/public/{$filename}");

            @mkdir(dirname($storagePath), 0755, true);
            file_put_contents($storagePath, $response->body());

            return "/storage/{$filename}";
        } catch (\Exception $e) {
            Log::error("NexusMods downloadImage error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync an existing Mod record from Nexus Mods API (fetch details, image, version, gameVersions).
     */
    public function syncModFromNexus(\App\Models\Mod $mod): bool
    {
        $nexusUrl = $mod->nexus_url;
        $nexusModId = $mod->nexus_mod_id;
        $game = $mod->game;

        if (!$game) {
            return false;
        }

        $gameDomain = $game->nexus_domain ?: $game->slug;

        // Try parsing Nexus URL if nexus_mod_id is missing
        if (!$nexusModId && $nexusUrl) {
            preg_match('/nexusmods\.com\/([^\/]+)\/mods\/(\d+)/i', $nexusUrl, $m);
            if (isset($m[1], $m[2])) {
                $gameDomain = $m[1];
                $nexusModId = (int)$m[2];
            }
        }

        if (!$nexusModId) {
            // Fallback: If no Nexus info, ensure at least game versions are attached so it's not "unknown"
            if ($mod->gameVersions()->count() === 0) {
                $allVersionIds = $game->versions()->pluck('id')->toArray();
                if (!empty($allVersionIds)) {
                    $mod->gameVersions()->sync($allVersionIds);
                }
            }
            return false;
        }

        $data = $this->fetchMod($gameDomain, $nexusModId);
        if (!$data) {
            return false;
        }

        $description = strip_tags($data['description'] ?? $data['summary'] ?? '');
        $imageUrl = $data['picture_url'] ?? $mod->image_url;
        $localPath = null;
        if ($imageUrl) {
            $localPath = $this->downloadImage($imageUrl, $mod->slug);
        }

        $mod->update([
            'nexus_mod_id'     => $nexusModId,
            'name'             => ($mod->name && !str_contains($mod->name, '??')) ? $mod->name : ($data['name'] ?? $mod->name),
            'description'      => !empty($description) ? $description : $mod->description,
            'author'           => $data['author'] ?? $mod->author,
            'version'          => $data['version'] ?? $mod->version,
            'downloads_count'  => ($data['mod_downloads'] ?? 0) + ($data['mod_unique_downloads'] ?? 0),
            'image_url'        => $imageUrl ?: $mod->image_url,
            'local_image_path' => $localPath ?: $mod->local_image_path,
            'tags'             => $this->extractTags($description),
            'fps_impact'       => $this->parseFpsImpact($description) ?: $mod->fps_impact,
            'nexus_url'        => "https://www.nexusmods.com/{$gameDomain}/mods/{$nexusModId}",
        ]);

        // Detect & sync compatible game versions and file size
        $files = $this->fetchModFiles($gameDomain, $nexusModId);
        $compatVersionIds = $this->detectCompatibleVersions($files, $game);

        $totalKb = 0;
        foreach ($files as $f) {
            $cat = strtoupper($f['category_name'] ?? '');
            if ($cat === 'MAIN' || $cat === 'UPDATE' || ($f['is_primary'] ?? false)) {
                $totalKb += (int)($f['size_kb'] ?? 0);
            }
        }

        if ($totalKb > 0) {
            $mod->update(['file_size_kb' => $totalKb]);
        }

        if (!empty($compatVersionIds)) {
            $mod->gameVersions()->sync($compatVersionIds);
        } else {
            // Fallback: attach all versions of this game if none explicitly matched
            $allVersionIds = $game->versions()->pluck('id')->toArray();
            if (!empty($allVersionIds)) {
                $mod->gameVersions()->sync($allVersionIds);
            }
        }

        return true;
    }
}
