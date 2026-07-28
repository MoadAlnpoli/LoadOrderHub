<?php

namespace App\Jobs;

use App\Models\Mod;
use App\Services\NexusModsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncNexusModsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NexusModsService $nexus): void
    {
        Log::info('SyncNexusModsJob: starting daily sync...');

        $mods = Mod::whereNotNull('nexus_mod_id')
                   ->whereNotNull('game_id')
                   ->with('game')
                   ->get();

        $updated = 0;

        foreach ($mods as $mod) {
            $gameDomain = $mod->game->slug ?? null;
            if (!$gameDomain || !$mod->nexus_mod_id) continue;

            $data = $nexus->fetchMod($gameDomain, $mod->nexus_mod_id);
            if (!$data) continue;

            $newVersion   = $data['version'] ?? null;
            $newDownloads = ($data['mod_downloads'] ?? 0) + ($data['mod_unique_downloads'] ?? 0);

            $mod->update([
                'downloads_count' => $newDownloads,
                'version'         => $newVersion ?: $mod->version,
            ]);

            $updated++;
            usleep(300000); // 0.3s delay to respect API rate limits
        }

        Log::info("SyncNexusModsJob: updated {$updated} mods.");
    }
}
