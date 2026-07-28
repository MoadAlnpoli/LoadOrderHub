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
        Schema::create('mods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mod_pack_id')->constrained('mod_packs')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('load_order');
            $table->string('nexus_url')->nullable();
            $table->timestamps();

            $table->unique(['mod_pack_id', 'load_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mods');
    }
};
