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
        Schema::create('mod_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_version_id')->constrained('game_versions')->onDelete('cascade');
            $table->string('title_en');
            $table->string('title_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->string('youtube_thumbnail_url')->nullable();
            $table->string('local_thumbnail_path')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('upvotes')->default(0);
            $table->unsignedInteger('downvotes')->default(0);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_packs');
    }
};
