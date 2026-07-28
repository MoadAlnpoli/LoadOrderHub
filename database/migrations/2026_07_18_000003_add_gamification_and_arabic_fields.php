<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('points')->default(100)->after('email');
            $table->string('badge_title', 100)->default('Novice Modder')->after('points');
            $table->string('avatar_url', 500)->nullable()->after('badge_title');
            $table->boolean('is_verified_curator')->default(false)->after('avatar_url');
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->text('description_ar')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'badge_title', 'avatar_url', 'is_verified_curator']);
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn(['description_ar']);
        });
    }
};
