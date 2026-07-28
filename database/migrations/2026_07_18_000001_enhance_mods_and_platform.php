<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->unsignedBigInteger('nexus_mod_id')->nullable()->after('id');
            $table->unsignedBigInteger('downloads_count')->default(0)->after('views_count');
            $table->string('version', 50)->nullable()->after('downloads_count');
            $table->string('author', 255)->nullable()->after('version');
            $table->json('tags')->nullable()->after('author');
            $table->string('fps_impact', 100)->nullable()->after('tags');
            $table->string('local_image_path', 255)->nullable()->after('fps_impact');
            $table->string('before_image_url', 500)->nullable()->change();
            $table->string('after_image_url', 500)->nullable()->change();
        });

        // Newsletter subscribers table
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('token', 64)->unique(); // for unsubscribe
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Affiliate links table
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('code', 32)->unique();
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
        });

        // Ad tracking: add impressions + clicks columns
        Schema::table('ad_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('impressions')->default(0)->after('is_active');
            $table->unsignedBigInteger('clicks')->default(0)->after('impressions');
        });
    }

    public function down(): void
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->dropColumn(['nexus_mod_id', 'downloads_count', 'version', 'author', 'tags', 'fps_impact', 'local_image_path']);
        });

        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('affiliate_links');

        Schema::table('ad_slots', function (Blueprint $table) {
            $table->dropColumn(['impressions', 'clicks']);
        });
    }
};
