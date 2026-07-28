<?php

namespace App\Http\Controllers;

use App\Models\ModPack;
use Illuminate\Http\Request;

class ModPackController extends Controller
{
    /**
     * Display the specified mod pack.
     */
    public function show(ModPack $modPack)
    {
        $modPack->increment('views_count');

        // Load relations
        $modPack->load([
            'gameVersions.game',
            'mods' => function ($query) {
                $query->orderBy('load_order', 'asc');
            },
            'creator',
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }
        ]);

        return view('mod_packs.show', compact('modPack'));
    }

    /**
     * Export the mod pack load order as a clean .txt file.
     */
    public function exportTxt(ModPack $modPack)
    {
        $modPack->load(['gameVersions.game', 'mods']);

        $gameName = $modPack->gameVersion->game->name;
        $version = $modPack->gameVersion->version;
        
        $content = "# " . __('messages.title') . " - {$modPack->title_en}\n";
        $content .= "# " . __('messages.game') . ": {$gameName} ({$version})\n";
        $content .= "# Youtube Video: https://youtube.com/watch?v={$modPack->youtube_video_id}\n";
        $content .= "# --------------------------------------------------\n\n";

        foreach ($modPack->mods as $mod) {
            $content .= "{$mod->name}\n";
        }

        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . str()->slug($modPack->title_en) . '-load-order.txt"',
        ];

        return response($content, 200, $headers);
    }

    public function embed(ModPack $modPack)
    {
        // Minimal UI for embedding
        return view('modpacks.embed', compact('modPack'));
    }

    public function exportJson(ModPack $modPack)
    {
        $modPack->load(['gameVersions.game', 'mods']);

        $gameName = $modPack->gameVersion->game->name;
        $version = $modPack->gameVersion->version;

        $modsData = [];
        foreach ($modPack->mods as $mod) {
            $modsData[] = [
                'name' => $mod->name,
                'load_order' => $mod->load_order,
                'nexus_url' => $mod->nexus_url,
                'steam_url' => $mod->steam_url,
                'download_url' => $mod->download_url,
            ];
        }

        $packJson = [
            'title_en' => $modPack->title_en,
            'title_ar' => $modPack->title_ar,
            'description_en' => $modPack->description_en,
            'description_ar' => $modPack->description_ar,
            'game' => $gameName,
            'version' => $version,
            'youtube_video_url' => $modPack->youtube_video_id ? "https://youtube.com/watch?v={$modPack->youtube_video_id}" : null,
            'mods' => $modsData,
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . str()->slug($modPack->title_en) . '-metadata.json"',
        ];

        return response(json_encode($packJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, $headers);
    }

    /**
     * Show the form for creating a new mod pack.
     */
    public function create()
    {
        $games = \App\Models\Game::with('versions')->get();
        $categories = \App\Models\Category::orderBy('name_en', 'asc')->get();
        return view('mod_packs.create', compact('games', 'categories'));
    }

    /**
     * Store a newly created mod pack in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'game_version_id' => 'required|exists:game_versions,id',
            'category_id' => 'required|exists:categories,id',
            'youtube_video_id' => 'nullable|string|max:50',
            'mods' => 'required|array|min:1',
            'mods.*.name' => 'required|string|max:255',
            'mods.*.load_order' => 'required|integer|min:1',
            'mods.*.id' => 'nullable|integer',
        ]);

        $gameVersion = \App\Models\GameVersion::findOrFail($request->game_version_id);

        // Validate dependencies
        $modIds = collect($request->mods)->pluck('id')->filter()->toArray();
        foreach ($modIds as $modId) {
            $originalMod = \App\Models\Mod::with('dependencies')->find($modId);
            if ($originalMod && $originalMod->dependencies->count() > 0) {
                foreach ($originalMod->dependencies as $dependency) {
                    if (!in_array($dependency->id, $modIds)) {
                        return back()->withInput()->withErrors(['mods' => "Mod '{$originalMod->name}' requires '{$dependency->name}', which is missing from your pack."]);
                    }
                }
            }
        }

        $modPack = ModPack::create([
            'category_id' => $request->category_id,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'created_by' => auth()->id(),
            'youtube_video_id' => $request->youtube_video_id,
            'is_published' => true,
            'is_private' => $request->has('is_private'),
        ]);

        // Attach game version to pivot
        $modPack->gameVersions()->attach($gameVersion->id);

        // Process mods list
        foreach ($request->mods as $m) {
            $originalMod = null;
            if (!empty($m['id'])) {
                $originalMod = \App\Models\Mod::find($m['id']);
            }

            $mod = \App\Models\Mod::create([
                'game_id' => $gameVersion->game_id,
                'mod_pack_id' => $modPack->id,
                'category_id' => $originalMod ? $originalMod->category_id : null,
                'name' => $m['name'],
                'load_order' => $m['load_order'],
                'slug' => str()->slug($m['name']),
                'nexus_url' => $originalMod ? $originalMod->nexus_url : null,
                'steam_url' => $originalMod ? $originalMod->steam_url : null,
                'download_url' => $originalMod ? $originalMod->download_url : null,
                'image_url' => $originalMod ? $originalMod->image_url : null,
            ]);

            $mod->gameVersions()->syncWithoutDetaching([$gameVersion->id]);
        }

        // Award Gamification Points (+50 points)
        if (auth()->user()) {
            auth()->user()->addPoints(50);
        }

        // Announce on Discord
        try {
            app(\App\Services\DiscordWebhookService::class)->announceNewPack($modPack);
        } catch (\Exception $e) {}

        return redirect()->route('modpacks.show', $modPack->id)
            ->with('success', 'Mod pack created successfully! +50 Points awarded 🎉');
    }

    /**
     * Export Mod Pack in Mod Organizer 2 (MO2) modlist.txt format.
     */
    public function exportMo2(ModPack $modPack)
    {
        $mods = $modPack->mods()->orderBy('load_order', 'asc')->get();

        $lines = [];
        $lines[] = "# This file was automatically generated by LoadOrderHub (https://loadorderhub.com)";
        $lines[] = "# ModPack: " . $modPack->title_en;
        $lines[] = "# Created by: " . ($modPack->creator->name ?? 'User');
        $lines[] = "# Total Mods: " . $mods->count();
        $lines[] = "# --------------------------------------------------";

        foreach ($mods as $mod) {
            $lines[] = "+" . $mod->name;
        }

        $content = implode("\r\n", $lines);
        $filename = str()->slug($modPack->title_en) . "-mo2-modlist.txt";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export Mod Pack as Markdown.
     */
    public function exportMarkdown(ModPack $modPack)
    {
        $mods = $modPack->mods()->orderBy('load_order', 'asc')->get();

        $lines = [];
        $lines[] = "# " . ($modPack->title_en ?: $modPack->title_ar);
        $lines[] = "**" . __('messages.game') . "**: " . ($modPack->gameVersion->game->name ?? 'Unknown');
        $lines[] = "**Author**: " . ($modPack->creator->name ?? 'User');
        $lines[] = "**Description**: " . ($modPack->description_en ?: $modPack->description_ar);
        $lines[] = "";
        $lines[] = "## Mod List (" . $mods->count() . " mods)";
        $lines[] = "";

        foreach ($mods as $index => $mod) {
            $link = $mod->nexus_url ?: ($mod->steam_url ?: route('mods.show', $mod->slug ?: $mod->id));
            $lines[] = ($index + 1) . ". [" . $mod->name . "](" . $link . ")";
            if ($mod->description) {
                $lines[] = "   > " . str_replace("\n", " ", strip_tags(str()->limit($mod->description, 100)));
            }
        }

        $lines[] = "";
        $lines[] = "---";
        $lines[] = "*Generated by [LoadOrderHub](https://loadorderhub.com)*";

        $content = implode("\r\n", $lines);
        $filename = str()->slug($modPack->title_en) . "-export.md";

        return response($content, 200, [
            'Content-Type' => 'text/markdown; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
