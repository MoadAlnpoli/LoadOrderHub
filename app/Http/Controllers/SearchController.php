<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Mod;
use App\Models\ModPack;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Search games, mods, and published modpacks.
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search Games
        $games = Game::where('name', 'like', "%{$q}%")
            ->take(4)
            ->get();
        foreach ($games as $game) {
            $results[] = [
                'type' => 'game',
                'title' => $game->name,
                'url' => route('games.show', $game->slug),
                'image' => $game->thumbnail_url ?: $game->thumbnail,
            ];
        }

        // Search Published ModPacks
        $modPacks = ModPack::where('is_published', true)
            ->where(function($query) use ($q) {
                $query->where('title_en', 'like', "%{$q}%")
                      ->orWhere('title_ar', 'like', "%{$q}%");
            })
            ->take(4)
            ->get();
        foreach ($modPacks as $mp) {
            $title = app()->getLocale() === 'ar' ? ($mp->title_ar ?: $mp->title_en) : ($mp->title_en ?: $mp->title_ar);
            $results[] = [
                'type' => 'modpack',
                'title' => $title,
                'url' => route('modpacks.show', $mp->id),
                'image' => $mp->youtube_thumbnail_url ?: ($mp->youtube_video_id ? "https://img.youtube.com/vi/{$mp->youtube_video_id}/hqdefault.jpg" : null),
            ];
        }

        // Search Unique Mods
        $mods = Mod::where('name', 'like', "%{$q}%")
            ->selectRaw('MIN(id) as id, name, slug, MAX(image_url) as image_url')
            ->groupBy('name', 'slug')
            ->take(4)
            ->get();
        foreach ($mods as $mod) {
            if ($mod->slug) {
                $results[] = [
                    'type' => 'mod',
                    'title' => $mod->name,
                    'url' => route('mods.show', $mod->slug),
                    'image' => $mod->image_url,
                ];
            }
        }

        return response()->json($results);
    }
}
