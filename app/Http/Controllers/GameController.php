<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameVersion;
use App\Models\ModPack;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of games.
     */
    public function index()
    {
        // Flush stale homepage cache to guarantee instant real-time updates
        \Illuminate\Support\Facades\Cache::forget('home_games');
        \Illuminate\Support\Facades\Cache::forget('home_trending_games');
        \Illuminate\Support\Facades\Cache::forget('home_latest_packs');
        \Illuminate\Support\Facades\Cache::forget('home_top_mods');
        \Illuminate\Support\Facades\Cache::forget('home_global_stats');

        $games = Game::withCount('versions')->get();

        $trendingGames = Game::withCount('versions')
            ->addSelect([
                'total_views' => \App\Models\ModPack::selectRaw('COALESCE(SUM(mod_packs.views_count), 0)')
                    ->join('game_version_mod_pack', 'mod_packs.id', '=', 'game_version_mod_pack.mod_pack_id')
                    ->join('game_versions', 'game_version_mod_pack.game_version_id', '=', 'game_versions.id')
                    ->whereColumn('game_versions.game_id', 'games.id')
            ])
            ->orderByDesc('total_views')
            ->take(6)
            ->get();

        $latestPacks = \App\Models\ModPack::with(['gameVersions.game', 'creator'])
            ->withCount('mods')
            ->latest()
            ->take(6)
            ->get();

        $topMods = \App\Models\Mod::with('game')
            ->latest()
            ->take(6)
            ->get();

        $globalStats = [
            'mods' => \App\Models\Mod::count(),
            'modpacks' => \App\Models\ModPack::count(),
            'users' => \App\Models\User::count(),
            'downloads' => \App\Models\Mod::sum('downloads_count') ?? 0,
        ];

        return view('games.index', compact('games', 'trendingGames', 'latestPacks', 'topMods', 'globalStats'));
    }

    /**
     * Display the specified game page with its versions.
     */
    public function show(Game $game, Request $request)
    {
        $versions = $game->versions()->orderBy('version', 'desc')->get();

        // Get the selected version ID. If none is selected, default to the latest version if available.
        $selectedVersionId = $request->get('version_id', $versions->first()?->id);

        $modPacks = [];
        if ($selectedVersionId) {
            $modPacks = ModPack::whereHas('gameVersions', function ($q) use ($selectedVersionId) {
                    $q->where('game_versions.id', $selectedVersionId);
                })
                ->where('is_published', true)
                ->with(['creator', 'gameVersions'])
                ->withCount('mods')
                ->get();
        }

        // Handle AJAX filtering request
        if ($request->ajax()) {
            return response()->json([
                'html' => view('games.partials.mod_packs_list', compact('modPacks'))->render(),
            ]);
        }

        $gameMods = Mod::where('game_id', $game->id)
            ->where('status', 'published')
            ->orderBy('name')
            ->take(12)
            ->get();

        return view('games.show', compact('game', 'versions', 'selectedVersionId', 'modPacks', 'gameMods'));
    }

    public function redirectLink(Request $request)
    {
        $encodedUrl = $request->get('url', '');
        $targetUrl = base64_decode($encodedUrl);

        if (empty($targetUrl) || !filter_var($targetUrl, FILTER_VALIDATE_URL)) {
            return redirect()->route('home');
        }

        // Mod Click Counter & Context
        $mod = null;
        $modId = $request->get('mod');
        if (!empty($modId)) {
            $mod = \App\Models\Mod::with('game')->find($modId);
            if ($mod) {
                $mod->increment('views_count');
                $mod->increment('downloads_count');
            }
        }

        // Extract host for display
        $parsedUrl = parse_url($targetUrl);
        $host = $parsedUrl['host'] ?? 'External Site';

        return view('download.gateway', compact('targetUrl', 'host', 'mod'));
    }
}
