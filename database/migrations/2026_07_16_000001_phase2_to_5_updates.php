<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter mods
        Schema::table('mods', function (Blueprint $table) {
            $table->string('status')->default('published')->after('has_issues'); // draft, published, rejected
            $table->string('before_image_url')->nullable()->after('image_url');
            $table->string('after_image_url')->nullable()->after('before_image_url');
        });

        // Alter mod_packs
        Schema::table('mod_packs', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title_en');
            $table->boolean('discord_webhook_sent')->default(false)->after('is_published');
        });

        // Alter games
        Schema::table('games', function (Blueprint $table) {
            $table->string('latest_version')->nullable()->after('slug');
        });

        // mod_dependencies
        Schema::create('mod_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mod_id')->constrained('mods')->onDelete('cascade');
            $table->foreignId('requires_mod_id')->constrained('mods')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['mod_id', 'requires_mod_id']);
        });

        // game_version_history
        Schema::create('game_version_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('version');
            $table->timestamp('detected_at')->useCurrent();
            $table->timestamps();
        });

        // mod_reports
        Schema::create('mod_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mod_id')->constrained('mods')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reason');
            $table->string('status')->default('active'); // active, resolved
            $table->timestamps();
        });

        // pack_ratings
        Schema::create('pack_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mod_pack_id')->constrained('mod_packs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating'); // 1-5
            $table->timestamps();
            $table->unique(['mod_pack_id', 'user_id']);
        });

        // ad_slots
        Schema::create('ad_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->timestamps();
        });

        // api_usage_tracking
        Schema::create('api_usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('api_name');
            $table->date('date');
            $table->integer('calls_count')->default(0);
            $table->timestamps();
            $table->unique(['api_name', 'date']);
        });

        // referral_tracking
        Schema::create('referral_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // discord, reddit, search
            $table->date('date');
            $table->integer('visits')->default(0);
            $table->timestamps();
            $table->unique(['source', 'date']);
        });
        
        // Auto generate slugs for existing mod_packs
        \App\Models\ModPack::whereNull('slug')->whereNotNull('title_en')->get()->each(function ($pack) {
            $pack->slug = \Illuminate\Support\Str::slug($pack->title_en) . '-' . $pack->id;
            $pack->save();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_tracking');
        Schema::dropIfExists('api_usage_tracking');
        Schema::dropIfExists('ad_slots');
        Schema::dropIfExists('pack_ratings');
        Schema::dropIfExists('mod_reports');
        Schema::dropIfExists('game_version_history');
        Schema::dropIfExists('mod_dependencies');

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('latest_version');
        });

        Schema::table('mod_packs', function (Blueprint $table) {
            $table->dropColumn(['slug', 'discord_webhook_sent']);
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn(['status', 'before_image_url', 'after_image_url']);
        });
    }
};
