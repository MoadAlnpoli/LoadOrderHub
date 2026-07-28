<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('categories');
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Add category_id to mods
        Schema::table('mods', function (Blueprint $table) {
            if (!Schema::hasColumn('mods', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            }
        });

        // Add category_id to mod_packs
        Schema::table('mod_packs', function (Blueprint $table) {
            if (!Schema::hasColumn('mod_packs', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            }
        });

        // Seed initial categories
        $categories = [
            [
                'name_en' => 'Challenge Runs',
                'name_ar' => 'تحديات اللعب',
                'slug' => 'challenge-runs',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Guides',
                'name_ar' => 'شروحات وأدلة',
                'slug' => 'guides',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => "Single-Video Let's Plays",
                'name_ar' => 'فيديو لعب منفرد',
                'slug' => 'single-video-lets-plays',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('categories')->insert($categories);
    }

    public function down(): void
    {
        Schema::table('mod_packs', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
