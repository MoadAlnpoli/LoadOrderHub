<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Mod;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Display the comparison view or return AJAX results for selection.
     */
    public function index(Request $request)
    {
        $games = Game::orderBy('name', 'asc')->get();
        
        $gameId = $request->get('game_id');
        $mod1Slug = $request->get('mod1');
        $mod2Slug = $request->get('mod2');

        $mod1 = null;
        $mod2 = null;

        if ($mod1Slug) {
            $mod1 = Mod::where('slug', $mod1Slug)->with('gameVersions')->first();
            if ($mod1) {
                $gameId = $mod1->game_id;
            }
        }
        
        if ($mod2Slug) {
            $mod2 = Mod::where('slug', $mod2Slug)->with('gameVersions')->first();
            if ($mod2 && !$gameId) {
                $gameId = $mod2->game_id;
            }
        }

        // AJAX handler for populating mod autocompletes inside the comparison page
        if ($request->ajax() && $request->has('search_mods')) {
            $q = $request->get('search_mods');
            $gId = $request->get('game_id');
            
            if (!$gId) {
                return response()->json([]);
            }

            $mods = Mod::where('game_id', $gId)
                ->where('name', 'like', "%{$q}%")
                ->selectRaw('MIN(id) as id, name, slug, MAX(image_url) as image_url')
                ->groupBy('name', 'slug')
                ->take(10)
                ->get();
                
            return response()->json($mods);
        }

        return view('mods.compare', compact('games', 'gameId', 'mod1', 'mod2'));
    }
}
