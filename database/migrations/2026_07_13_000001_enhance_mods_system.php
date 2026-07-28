<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns to mods table
        Schema::table('mods', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('steam_url')->nullable()->after('download_url');
            $table->foreignId('game_id')->nullable()->after('mod_pack_id')->constrained('games')->onDelete('set null');
        });

        // Create pivot table: which game versions does each mod support
        Schema::create('game_version_mod', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_version_id')->constrained('game_versions')->onDelete('cascade');
            $table->foreignId('mod_id')->constrained('mods')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['game_version_id', 'mod_id']);
        });

        // Add mod_id to comments table for polymorphic-like comments on mods
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('mod_id')->nullable()->after('mod_pack_id')->constrained('mods')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['mod_id']);
            $table->dropColumn('mod_id');
        });

        Schema::dropIfExists('game_version_mod');

        Schema::table('mods', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
            $table->dropColumn(['slug', 'steam_url', 'game_id']);
        });
    }
};
