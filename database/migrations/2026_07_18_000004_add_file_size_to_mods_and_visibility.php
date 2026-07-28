<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->integer('file_size_kb')->nullable()->after('version');
        });

        Schema::table('mod_packs', function (Blueprint $table) {
            $table->boolean('is_private')->default(false)->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn('file_size_kb');
        });

        Schema::table('mod_packs', function (Blueprint $table) {
            $table->dropColumn('is_private');
        });
    }
};
