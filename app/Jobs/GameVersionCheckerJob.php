<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Game;
use App\Models\GameVersionHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GameVersionCheckerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $games = Game::all();

        foreach ($games as $game) {
            try {
                // Here we would integrate with Steam API or RAWG.
                // For demonstration, we simulate fetching the latest version.
                // e.g. $response = Http::get("https://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?appid={$game->steam_id}&key=...");
                
                // Simulated new version logic
                $latestDetectedVersion = 'v' . date('Y.m.d'); 
                
                if ($game->latest_version !== $latestDetectedVersion) {
                    $game->latest_version = $latestDetectedVersion;
                    $game->save();

                    GameVersionHistory::create([
                        'game_id' => $game->id,
                        'version' => $latestDetectedVersion,
                        'detected_at' => now(),
                    ]);
                    
                    // Trigger notifications for users who have packs for this game...
                    // (Implementation depends on the notification system used in the project)
                    Log::info("New game version detected for {$game->name}: {$latestDetectedVersion}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to check version for game {$game->name}: " . $e->getMessage());
            }
        }
    }
}
