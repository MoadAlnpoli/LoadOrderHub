<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_version_mod_pack', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mod_pack_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_version_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing game_version_id mappings if the column exists
        if (Schema::hasColumn('mod_packs', 'game_version_id')) {
            $existing = DB::table('mod_packs')->select('id', 'game_version_id')->get();
            foreach ($existing as $row) {
                if ($row->game_version_id) {
                    DB::table('game_version_mod_pack')->insert([
                        'mod_pack_id' => $row->id,
                        'game_version_id' => $row->game_version_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Drop foreign key and column
            Schema::table('mod_packs', function (Blueprint $table) {
                $table->dropForeign(['game_version_id']);
                $table->dropColumn('game_version_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mod_packs', function (Blueprint $table) {
            $table->unsignedBigInteger('game_version_id')->nullable();
        });

        // Re-copy back relationships
        $existing = DB::table('game_version_mod_pack')->get();
        foreach ($existing as $row) {
            DB::table('mod_packs')
                ->where('id', $row->mod_pack_id)
                ->update(['game_version_id' => $row->game_version_id]);
        }

        Schema::table('mod_packs', function (Blueprint $table) {
            $table->foreign('game_version_id')->references('id')->on('game_versions')->onDelete('cascade');
        });

        Schema::dropIfExists('game_version_mod_pack');
    }
};
