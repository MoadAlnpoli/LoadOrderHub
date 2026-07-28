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
        if (Schema::hasColumn('games', 'slug')) {
            return;
        }

        Schema::table('games', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slugs for existing games
        $games = DB::table('games')->get();
        foreach ($games as $g) {
            $slug = Str::slug($g->name);
            // Check if slug is unique, append ID if needed
            $count = DB::table('games')->where('slug', $slug)->where('id', '!=', $g->id)->count();
            if ($count > 0) {
                $slug .= '-' . $g->id;
            }

            DB::table('games')->where('id', $g->id)->update(['slug' => $slug]);
        }

        // Alter slug to be unique and NOT nullable
        try {
            Schema::table('games', function (Blueprint $table) {
                $table->string('slug')->nullable(false)->unique()->change();
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name')) {
                throw $e;
            }
        }
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
