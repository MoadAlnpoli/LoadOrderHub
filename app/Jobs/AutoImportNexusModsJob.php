<?php

namespace App\Jobs;

use App\Models\Game;
use App\Models\Mod;
use App\Services\NexusModsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Auto-imports top mods from Nexus Mods API for all enabled games.
 * Runs daily via Kernel scheduler.
 */
class AutoImportNexusModsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Nexus Mods "updated" sort options: 'endorsements', 'downloads', 'updated', 'latest_added'
    protected string $sortBy;
    protected ?int   $gameId;

    public function __construct(string $sortBy = 'endorsements', ?int $gameId = null)
    {
        $this->sortBy = $sortBy;
        $this->gameId = $gameId;
    }

    public function handle(NexusModsService $nexus): void
    {
        $apiKey = config('services.nexus.api_key');

        if (!$apiKey) {
            Log::warning('AutoImportNexusModsJob: NEXUS_API_KEY not set.');
            return;
        }

        // Get all games that have auto_import_enabled or a specific gameId
        $query = Game::whereNotNull('nexus_domain');

        if ($this->gameId) {
            $query->where('id', $this->gameId);
        } else {
            $query->where('auto_import_enabled', true);
        }

        $games = $query->get();

        if ($games->isEmpty()) {
            Log::info('AutoImportNexusModsJob: No games have auto_import_enabled or nexus_domain set.');
            return;
        }

        foreach ($games as $game) {
            $this->importForGame($game, $nexus, $apiKey);

            // Respect API rate limits between games
            sleep(2);
        }
    }

    protected function importForGame(Game $game, NexusModsService $nexus, string $apiKey): void
    {
        $domain = $game->nexus_domain;
        $limit  = $game->auto_import_limit ?? 20;

        Log::info("AutoImportNexusModsJob: Fetching top {$limit} mods for {$domain}...");

        try {
            // Nexus Mods API endpoint for listing mods by game
            $response = Http::withHeaders([
                'apiKey'     => $apiKey,
                'Accept'     => 'application/json',
                'User-Agent' => 'LoadOrderHub/1.0',
            ])->get("https://api.nexusmods.com/v1/games/{$domain}/mods/{$this->sortBy}.json", [
                'limit' => min($limit, 50),
            ]);

            if (!$response->successful()) {
                Log::warning("AutoImportNexusModsJob: API error for {$domain}: " . $response->status());
                return;
            }

            $mods = $response->json() ?? [];

            $imported = 0;
            foreach ($mods as $modData) {
                $nexusModId = $modData['mod_id'] ?? null;
                if (!$nexusModId) continue;

                // Skip if already imported
                if (Mod::where('nexus_mod_id', $nexusModId)->where('game_id', $game->id)->exists()) {
                    continue;
                }

                $this->saveMod($modData, $game, $nexus);
                $imported++;
                usleep(500000); // 0.5s delay per mod
            }

            // Update last_imported_at timestamp
            $game->update(['last_imported_at' => now()]);

            Log::info("AutoImportNexusModsJob: Imported {$imported} new mods for {$domain}.");
        } catch (\Exception $e) {
            Log::error("AutoImportNexusModsJob error for {$domain}: " . $e->getMessage());
        }
    }

    protected function saveMod(array $data, Game $game, NexusModsService $nexus): void
    {
        $name    = $data['name'] ?? 'Unknown Mod';
        $slug    = Str::slug($name) ?: 'mod-' . ($data['mod_id'] ?? rand());
        $baseSlug = $slug;
        $i = 1;
        while (Mod::where('slug', $slug)->where('game_id', $game->id)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $description   = strip_tags($data['summary'] ?? '');
        $imageUrl      = $data['picture_url'] ?? null;
        $localImagePath = null;

        // Download image
        if ($imageUrl) {
            $localImagePath = $nexus->downloadImage($imageUrl, $slug);
        }

        $tags      = $nexus->extractTags($description);
        $fpsImpact = $nexus->parseFpsImpact($description);
        $nexusUrl  = "https://www.nexusmods.com/{$game->nexus_domain}/mods/{$data['mod_id']}";

        Mod::create([
            'game_id'          => $game->id,
            'nexus_mod_id'     => $data['mod_id'],
            'name'             => $name,
            'slug'             => $slug,
            'description'      => $description,
            'image_url'        => $imageUrl,
            'local_image_path' => $localImagePath,
            'nexus_url'        => $nexusUrl,
            'author'           => $data['author'] ?? null,
            'version'          => $data['version'] ?? null,
            'downloads_count'  => ($data['mod_downloads'] ?? 0),
            'tags'             => $tags,
            'fps_impact'       => $fpsImpact,
            'status'           => 'published',
        ]);
    }
}
