<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('extraction_logs');
        Schema::create('extraction_logs', function (Blueprint $table) {
            $table->id();
            $table->string('video_id');
            $table->string('title');
            $table->boolean('transcript_fetched')->default(false);
            $table->text('failure_reason')->nullable();
            $table->boolean('is_valid_json')->default(false);
            $table->integer('total_mods_extracted')->default(0);
            $table->integer('low_confidence_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extraction_logs');
    }
};
