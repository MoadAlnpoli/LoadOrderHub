<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds issue tracking fields to the mods table.
     */
    public function up(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->boolean('has_issues')->default(false)->after('image_url');
            $table->unsignedInteger('issues_count')->default(0)->after('has_issues');
            $table->text('issues_note')->nullable()->after('issues_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn(['has_issues', 'issues_count', 'issues_note']);
        });
    }
};
