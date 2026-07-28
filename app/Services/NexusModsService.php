<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * Uses proper User-Agent headers to bypass CDN hotlink restrictions.
     * Returns the stored relative path or null on failure.
     */
    public function downloadImage(string $url, string $slug): ?string
    {
        try {
            // Skip if URL is from our own domain to avoid loop
            if (str_contains($url, request()->getHost())) {
                return null;
            }

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept'     => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
            ])->timeout(30)->get($url);

            if (!$response->successful()) {
                Log::warning("NexusMods downloadImage: HTTP {$response->status()} for {$url}");
                return null;
            }

            $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $ext = explode('?', $ext)[0]; // Strip query params from extension
            $filename = "mods/{$slug}." . $ext;

            Storage::disk('public')->put($filename, $response->body());

            return 'storage/' . $filename;
        } catch (\Exception $e) {
            Log::error("NexusMods downloadImage error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch mod details from Nexus API and return a normalized array.
     * Combines data from the mod details endpoint, images endpoint, and files endpoint.
     */
    public function fetchModWithFullDetails(string $gameDomain, int $modId, ?\App\Models\Game $game = null): ?array
    {
        $data = $this->fetchMod($gameDomain, $modId);
        if (!$data) {
            return null;
        }

        $description = strip_tags($data['description'] ?? $data['summary'] ?? '');
        $pictureUrl  = $data['picture_url'] ?? null;
        $categoryName = $data['category_name'] ?? null;

        // Fetch files to determine size
        $files = $this->fetchModFiles($gameDomain, $modId);
        $totalKb = 0;
        foreach ($files as $f) {
            $cat = strtoupper($f['category_name'] ?? '');
            if ($cat === 'MAIN' || $cat === 'UPDATE' || ($f['is_primary'] ?? false)) {
                $totalKb += (int)($f['size_kb'] ?? 0);
            }
        }

        // Detect compatible game versions
        $compatVersionIds = [];
        if ($game) {
            $compatVersionIds = $this->detectCompatibleVersions($files, $game);
        }

        return [
            'name'               => $data['name'] ?? null,
            'description'        => $description,
            'summary'            => $data['summary'] ?? null,
            'author'             => $data['author'] ?? null,
            'version'            => $data['version'] ?? null,
            'picture_url'        => $pictureUrl,
            'category_name'      => $categoryName,
            'downloads_count'    => ($data['mod_downloads'] ?? 0) + ($data['mod_unique_downloads'] ?? 0),
            'endorsement_count'  => $data['endorsement_count'] ?? 0,
            'file_size_kb'       => $totalKb,
            'nexus_url'          => "https://www.nexusmods.com/{$gameDomain}/mods/{$modId}",
            'compat_version_ids' => $compatVersionIds,
            'tags'               => $this->extractTags($description, $categoryName ? [$categoryName] : []),
            'fps_impact'         => $this->parseFpsImpact($description),
        ];
    }

    /**
     * Sync an existing Mod record from Nexus Mods API.
     * Fetches details, downloads image locally, updates metadata gracefully.
     */
    public function syncModFromNexus(\App\Models\Mod $mod): bool
    {
        $nexusUrl   = $mod->nexus_url;
        $nexusModId = $mod->nexus_mod_id;
        $game       = $mod->game;

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
            // Fallback: If no Nexus info, ensure at least game versions are attached
            if ($mod->gameVersions()->count() === 0) {
                $allVersionIds = $game->versions()->pluck('id')->toArray();
                if (!empty($allVersionIds)) {
                    $mod->gameVersions()->sync($allVersionIds);
                }
            }
            return false;
        }

        // Use the unified fetch method
        $details = $this->fetchModWithFullDetails($gameDomain, $nexusModId, $game);
        if (!$details) {
            return false;
        }

        // Always try to download image locally for self-sufficiency
        $imageUrl  = $details['picture_url'] ?? $mod->image_url;
        $localPath = $mod->local_image_path;

        if ($imageUrl && empty($localPath)) {
            // Try ImageService first (more robust), fall back to our own method
            $localPath = ImageService::downloadAndSaveImage($imageUrl, 'mods');
            if (!$localPath) {
                $localPath = $this->downloadImage($imageUrl, $mod->slug);
            }
        }

        // Graceful update: only overwrite fields if API returned non-empty values
        $updateData = [
            'nexus_mod_id' => $nexusModId,
            'nexus_url'    => $details['nexus_url'],
        ];

        // Name: only update if current name looks broken or is empty
        if (empty($mod->name) || str_contains($mod->name, '??')) {
            $updateData['name'] = $details['name'] ?? $mod->name;
        }

        // Description: update only if API has one and current is empty/short
        if (!empty($details['description']) && (empty($mod->description) || strlen($mod->description) < 20)) {
            $updateData['description'] = $details['description'];
        }

        // Author, version: update if API provides and current is empty
        if (!empty($details['author']) && empty($mod->author)) {
            $updateData['author'] = $details['author'];
        }
        if (!empty($details['version']) && empty($mod->version)) {
            $updateData['version'] = $details['version'];
        }

        // Downloads: always take the latest from API (it's cumulative)
        if ($details['downloads_count'] > 0) {
            $updateData['downloads_count'] = $details['downloads_count'];
        }

        // Image: update if API provides one
        if (!empty($imageUrl)) {
            $updateData['image_url'] = $imageUrl;
        }
        if (!empty($localPath)) {
            $updateData['local_image_path'] = $localPath;
        }

        // Tags and FPS impact
        if (!empty($details['tags'])) {
            $updateData['tags'] = $details['tags'];
        }
        if (!empty($details['fps_impact'])) {
            $updateData['fps_impact'] = $details['fps_impact'];
        }

        // File size
        if ($details['file_size_kb'] > 0) {
            $updateData['file_size_kb'] = $details['file_size_kb'];
        }

        $mod->update($updateData);

        // Sync compatible game versions
        $compatVersionIds = $details['compat_version_ids'] ?? [];
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
