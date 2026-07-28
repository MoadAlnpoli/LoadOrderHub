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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportNexusModJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $gameDomain,
        protected int    $nexusModId,
        protected int    $gameId
    ) {}

    public function handle(NexusModsService $nexus): void
    {
        Log::info("ImportNexusModJob: fetching mod {$this->nexusModId} for game {$this->gameDomain}");

        $data = $nexus->fetchMod($this->gameDomain, $this->nexusModId);
        if (!$data) {
            Log::warning("ImportNexusModJob: no data returned for mod {$this->nexusModId}");
            return;
        }

        $game = Game::find($this->gameId);
        if (!$game) {
            Log::error("ImportNexusModJob: game {$this->gameId} not found");
            return;
        }

        // Prepare slug
        $name = $data['name'] ?? 'Unknown Mod ' . $this->nexusModId;
        $slug = Str::slug($name) ?: 'mod-' . $this->nexusModId;

        // Ensure slug uniqueness
        $baseSlug = $slug;
        $counter  = 1;
        while (Mod::where('slug', $slug)->where('game_id', $this->gameId)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // Download primary image
        $imageUrl      = $data['picture_url'] ?? null;
        $localImagePath = null;
        if ($imageUrl) {
            $localImagePath = $nexus->downloadImage($imageUrl, $slug);
        }

        // Extract tags from description
        $description = $data['description'] ?? $data['summary'] ?? '';
        $tags        = $nexus->extractTags($description, [$data['category_name'] ?? '']);
        $fpsImpact   = $nexus->parseFpsImpact($description);

        // Detect compatible game versions from files
        $files            = $nexus->fetchModFiles($this->gameDomain, $this->nexusModId);
        $compatVersionIds = $nexus->detectCompatibleVersions($files, $game);

        // Upsert the mod record
        $mod = Mod::updateOrCreate(
            ['nexus_mod_id' => $this->nexusModId, 'game_id' => $this->gameId],
            [
                'name'            => $name,
                'slug'            => $slug,
                'description'     => strip_tags($description),
                'image_url'       => $imageUrl,
                'local_image_path'=> $localImagePath,
                'nexus_url'       => "https://www.nexusmods.com/{$this->gameDomain}/mods/{$this->nexusModId}",
                'author'          => $data['author'] ?? null,
                'version'         => $data['version'] ?? null,
                'downloads_count' => ($data['mod_downloads'] ?? 0) + ($data['mod_unique_downloads'] ?? 0),
                'tags'            => $tags,
                'fps_impact'      => $fpsImpact,
                'status'          => 'published',
                'game_id'         => $this->gameId,
            ]
        );

        // Sync compatible game versions
        if (!empty($compatVersionIds)) {
            $mod->gameVersions()->syncWithoutDetaching($compatVersionIds);
        }

        Log::info("ImportNexusModJob: mod '{$name}' saved (ID: {$mod->id})");

        // Notify Discord about new mod
        try {
            app(\App\Services\DiscordWebhookService::class)->announceNewMod($mod);
        } catch (\Exception $e) {
            Log::warning('Discord notify failed: ' . $e->getMessage());
        }
    }
}
