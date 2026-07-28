<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Link existing mods to their game_id and game versions
        $modPacks = DB::table('mod_packs')->get();

        foreach ($modPacks as $pack) {
            // Find the game version associated with this modpack
            $pivotEntries = DB::table('game_version_mod_pack')
                ->where('mod_pack_id', $pack->id)
                ->get();

            if ($pivotEntries->isEmpty()) {
                continue;
            }

            // Get game_id from the first version
            $firstVersion = DB::table('game_versions')
                ->where('id', $pivotEntries->first()->game_version_id)
                ->first();

            if (!$firstVersion) {
                continue;
            }

            $gameId = $firstVersion->game_id;

            // Get all mods belonging to this modpack
            $mods = DB::table('mods')
                ->where('mod_pack_id', $pack->id)
                ->get();

            foreach ($mods as $mod) {
                // Generate slug
                $slug = \Illuminate\Support\Str::slug($mod->name);

                // Update mod game_id and slug
                DB::table('mods')
                    ->where('id', $mod->id)
                    ->update([
                        'game_id' => $gameId,
                        'slug' => $slug,
                    ]);

                // Link to all versions of this modpack
                foreach ($pivotEntries as $pivot) {
                    // Avoid duplicate insertion
                    $exists = DB::table('game_version_mod')
                        ->where('game_version_id', $pivot->game_version_id)
                        ->where('mod_id', $mod->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('game_version_mod')->insert([
                            'game_version_id' => $pivot->game_version_id,
                            'mod_id' => $mod->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // No down action needed for data migration
    }
};
