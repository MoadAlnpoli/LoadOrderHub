<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('video_staging');
        Schema::create('video_staging', function (Blueprint $table) {
            $table->id();
            $table->string('video_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('game_id')->nullable()->constrained('games')->onDelete('set null');
            $table->boolean('processed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_staging');
    }
};
