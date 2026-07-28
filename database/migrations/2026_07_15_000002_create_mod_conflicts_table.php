<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the mod_conflicts table to track incompatible mods.
     */
    public function up(): void
    {
        Schema::create('mod_conflicts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mod_id');
            $table->unsignedBigInteger('conflicts_with_mod_id');
            $table->string('reason')->nullable(); // سبب التعارض
            $table->timestamps();

            $table->foreign('mod_id')->references('id')->on('mods')->onDelete('cascade');
            $table->foreign('conflicts_with_mod_id')->references('id')->on('mods')->onDelete('cascade');
            $table->unique(['mod_id', 'conflicts_with_mod_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_conflicts');
    }
};
