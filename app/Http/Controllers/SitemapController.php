<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Mod;
use App\Models\ModPack;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml.
     */
    public function index(): Response
    {
        $games = Game::orderBy('updated_at', 'desc')->get();
        
        // Group mods by name/slug to have unique canonical pages
        $mods = Mod::select('slug', 'updated_at')
            ->groupBy('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $modPacks = ModPack::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('sitemap', compact('games', 'mods', 'modPacks'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
