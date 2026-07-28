<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add nexus_domain to games table for auto-import
        Schema::table('games', function (Blueprint $table) {
            $table->string('nexus_domain', 100)->nullable()->after('slug')
                  ->comment('Nexus Mods game domain slug, e.g. skyrim, skyrimspecialedition, fallout4');
            $table->boolean('auto_import_enabled')->default(false)->after('nexus_domain');
            $table->integer('auto_import_limit')->default(20)->after('auto_import_enabled');
            $table->timestamp('last_imported_at')->nullable()->after('auto_import_limit');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['nexus_domain', 'auto_import_enabled', 'auto_import_limit', 'last_imported_at']);
        });
    }
};
