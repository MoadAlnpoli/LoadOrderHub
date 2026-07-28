<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Mod;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModController extends Controller
{
    /**
     * Display a listing of mods for a specific game.
     */
    public function index(Game $game, Request $request)
    {
        $search = $request->get('search', '');
        $versionId = $request->get('version_id', '');

        $query = Mod::where('game_id', $game->id)
            ->with(['game', 'gameVersions', 'modPack'])
            ->withCount(['comments'])
            ->where('status', 'published');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($versionId)) {
            $query->whereHas('gameVersions', function ($q) use ($versionId) {
                $q->where('game_versions.id', $versionId);
            });
        }

        $mods = $query->orderBy('name', 'asc')->paginate(20);

        $versions = $game->versions()->orderBy('version', 'desc')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('mods.partials.mods_list_table', compact('mods', 'game'))->render(),
            ]);
        }
        return view('mods.index', compact('game', 'mods', 'versions', 'versionId', 'search'));
    }

    /**
     * Display a listing of popular/trending mods.
     */
    public function trending(Request $request)
    {
        $selectedGameId = $request->get('game_id', '');
        
        $query = Mod::where('status', 'published')->selectRaw('MIN(id) as id, name, slug, MAX(image_url) as image_url, SUM(views_count) as total_views, MAX(game_id) as game_id')
            ->groupBy('name', 'slug');

        if (!empty($selectedGameId)) {
            $query->where('game_id', $selectedGameId);
        }

        $mods = $query->orderBy('total_views', 'desc')->paginate(15);
        
        // Add comments count and clean relations
        $mods->getCollection()->transform(function ($mod) {
            $modIds = Mod::where('name', $mod->name)->pluck('id');
            $mod->comments_count = Comment::whereIn('mod_id', $modIds)->count();
            $mod->game = Game::find($mod->game_id);
            return $mod;
        });

        $games = Game::orderBy('name', 'asc')->get();

        return view('mods.trending', compact('mods', 'games', 'selectedGameId'));
    }

    /**
     * Display a listing of top mods weekly.
     */
    public function topWeekly(Request $request)
    {
        $selectedGameId = $request->get('game_id', '');

        $query = Mod::where('status', 'published')
            ->where('updated_at', '>=', now()->subDays(7))
            ->selectRaw('MIN(id) as id, name, slug, MAX(image_url) as image_url, MAX(local_image_path) as local_image_path, SUM(views_count) as total_views, MAX(game_id) as game_id')
            ->groupBy('name', 'slug');

        if (!empty($selectedGameId)) {
            $query->where('game_id', $selectedGameId);
        }

        $mods = $query->orderBy('total_views', 'desc')->paginate(12);

        // Attach comments count & clean relations
        $mods->getCollection()->transform(function ($mod) {
            $modIds = Mod::where('name', $mod->name)->pluck('id');
            $mod->comments_count = Comment::whereIn('mod_id', $modIds)->count();
            $mod->game = Game::find($mod->game_id);
            return $mod;
        });

        $games = Game::orderBy('name', 'asc')->get();

        return view('mods.top-weekly', compact('mods', 'games', 'selectedGameId'));
    }

    /**
     * Display a comprehensive mods catalog explorer.
     */
    public function explorer(Request $request)
    {
        $selectedGameId = $request->get('game_id', '');
        $selectedVersionId = $request->get('version_id', '');
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'views_desc');
        $minRating = $request->get('min_rating', '');

        $query = Mod::where('status', 'published')->selectRaw('MIN(mods.id) as id, mods.name, mods.slug, MAX(mods.image_url) as image_url, SUM(mods.views_count) as total_views, MAX(mods.game_id) as game_id')
            ->groupBy('mods.name', 'mods.slug');

        if (!empty($minRating)) {
            $query->leftJoin('comments', 'mods.id', '=', 'comments.mod_id')
                ->havingRaw('AVG(comments.rating_stars) >= ?', [$minRating]);
        }

        if (!empty($selectedGameId)) {
            $query->where('game_id', $selectedGameId);
        }

        if (!empty($selectedVersionId)) {
            $query->whereHas('gameVersions', function ($q) use ($selectedVersionId) {
                $q->where('game_versions.id', $selectedVersionId);
            });
        }

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('total_views', 'desc');
        }

        $mods = $query->paginate(12);

        // Attach comments count & clean relations
        $mods->getCollection()->transform(function ($mod) {
            $modIds = Mod::where('name', $mod->name)->pluck('id');
            $mod->comments_count = Comment::whereIn('mod_id', $modIds)->count();
            $mod->game = Game::find($mod->game_id);
            return $mod;
        });

        $games = Game::orderBy('name', 'asc')->get();
        
        $versions = [];
        if (!empty($selectedGameId)) {
            $versions = \App\Models\GameVersion::where('game_id', $selectedGameId)->get();
        }

        return view('mods.explorer', compact('mods', 'games', 'versions', 'selectedGameId', 'selectedVersionId', 'search', 'sort'));
    }

    /**
     * Display the specified mod.
     */
    public function show(string $slug)
    {
        $mod = Mod::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        if ($mod->status !== 'published' && !(auth()->check() && auth()->user()->is_admin)) {
            abort(404, 'Mod not found or not published.');
        }

        $mod->load(['game', 'gameVersions', 'comments.user', 'comments.replies.user', 'modPack.creator', 'dependencies', 'dependents', 'reports']);
        
        // Increment views count safely
        $mod->increment('views_count');
        
        $relatedModPacks = \App\Models\ModPack::whereHas('mods', function ($q) use ($mod) {
            $q->where('name', $mod->name);
        })
        ->where('is_published', true)
        ->with(['gameVersions', 'creator'])
        ->get();

        // Get top 5 most viewed recommended mods from the same game
        $recommendedMods = Mod::where('game_id', $mod->game_id)
            ->where('name', '!=', $mod->name)
            ->selectRaw('MIN(id) as id, name, slug, MAX(image_url) as image_url, SUM(views_count) as total_views')
            ->groupBy('name', 'slug')
            ->orderBy('total_views', 'desc')
            ->take(5)
            ->get();

        return view('mods.show', compact('mod', 'relatedModPacks', 'recommendedMods'));
    }

    /**
     * Return Quick View HTML for a mod
     */
    public function quickView(Mod $mod)
    {
        return response()->json([
            'html' => '
                <div class="space-y-4">
                    <div class="aspect-[21/9] w-full rounded-xl overflow-hidden bg-slate-950 relative">
                        '. ($mod->image_url ? '<img src="'.$mod->image_url.'" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center text-slate-700"><i class="fa-solid fa-cube text-4xl"></i></div>') .'
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2 py-1 rounded bg-violet-600/10 border border-violet-500/20 text-xs font-bold text-violet-400">'.$mod->game?->name.'</span>
                        <span class="px-2 py-1 rounded bg-slate-800 text-xs font-bold text-slate-400"><i class="fa-regular fa-eye mr-1"></i> '.number_format($mod->total_views).' Views</span>
                    </div>
                    <h2 class="text-xl font-bold text-white">'.$mod->name.'</h2>
                    <p class="text-sm text-slate-300 line-clamp-4">'.strip_tags($mod->description ?: 'No description available.').'</p>
                    <div class="pt-4 flex gap-3">
                        <a href="'.route('mods.show', $mod->slug).'" class="flex-1 py-2 bg-gradient-to-r from-violet-600 to-blue-500 hover:from-violet-500 hover:to-blue-400 text-white text-sm font-bold rounded-xl text-center transition-all shadow-md">View Full Details</a>
                    </div>
                </div>'
        ]);
    }

    /**
     * Store a comment/reply for the mod.
     */
    public function storeComment(Request $request, Mod $mod)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'mod_id' => $mod->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'parent_id' => $comment->parent_id,
                ]
            ]);
        }

        return back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Display form to add a mod manually.
     */
    public function create()
    {
        $games = Game::orderBy('name', 'asc')->get();
        $categories = \App\Models\Category::orderBy('name_en', 'asc')->get();
        return view('mods.create', compact('games', 'categories'));
    }

    /**
     * Store a manually entered mod in the database.
     */
    public function store(\App\Http\Requests\StoreModRequest $request)
    {
        $validated = $request->validated();

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $dir = public_path('uploads/mods');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $fileName = time() . '_' . Str::slug($validated['name_en']) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $fileName);
            $imageUrl = asset('uploads/mods/' . $fileName);
        }

        // Create the Mod
        $mod = Mod::create([
            'game_id' => $validated['game_id'],
            'category_id' => $validated['category_id'],
            'name' => $validated['name_en'],
            'slug' => Str::slug($validated['name_en']),
            'description' => $validated['description_en'] ?: $validated['description_ar'] ?: null,
            'load_order' => 1,
            'nexus_url' => $validated['nexus_url'] ?? null,
            'steam_url' => $validated['steam_url'] ?? null,
            'download_url' => $validated['nexus_url'] ?? $validated['steam_url'] ?? null,
            'image_url' => $imageUrl,
        ]);

        // Auto-fetch data from Nexus Mods API if nexus_url is available
        $nexusService = app(\App\Services\NexusModsService::class);
        $nexusService->syncModFromNexus($mod);

        // Ensure at least game versions are attached if none exist
        if ($mod->gameVersions()->count() === 0 && $mod->game && $mod->game->versions->isNotEmpty()) {
            $mod->gameVersions()->sync($mod->game->versions->pluck('id'));
        }

        // Sync conflicts
        if (!empty($validated['conflicts'])) {
            foreach ($validated['conflicts'] as $conflictingModId) {
                $reasonEn = $validated['conflict_reasons_en'][$conflictingModId] ?? 'Incompatible mods - might cause conflicts.';
                $reasonAr = $validated['conflict_reasons_ar'][$conflictingModId] ?? 'مودات غير متوافقة - قد تسبب تعارض في الملفات.';
                
                // Ensure bidirectional unique insert: sort mod_ids to avoid duplicates
                $minId = min($mod->id, $conflictingModId);
                $maxId = max($mod->id, $conflictingModId);

                \DB::table('mod_conflicts')->updateOrInsert(
                    ['mod_id' => $minId, 'conflicts_with_mod_id' => $maxId],
                    ['reason_en' => $reasonEn, 'reason_ar' => $reasonAr, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        return redirect()->route('admin.dashboard', ['mods_page' => 1])
            ->with('success', 'تم إضافة المود اليدوي وحفظ تعارضاته بنجاح!');
    }

    /**
     * Autocomplete search for mods by game ID.
     */
    public function searchModsByGame(Request $request)
    {
        $gameId = $request->get('game_id');
        $q = $request->get('q', '');
        $categoryId = $request->get('category_id');

        $query = Mod::where('game_id', $gameId)
            ->whereNull('mod_pack_id')
            ->where('name', 'like', "%{$q}%");

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $mods = $query->with(['category'])
            ->take(100)
            ->get();

        return response()->json($mods);
    }

    /**
     * AJAX endpoint to check conflicts between selected mods.
     */
    public function checkConflicts(Request $request)
    {
        $modIds = $request->get('mod_ids', []);
        if (empty($modIds)) {
            return response()->json(['conflicts' => [], 'score' => 100]);
        }

        $conflicts = [];
        $totalMods = count($modIds);
        $totalPossiblePairs = ($totalMods * ($totalMods - 1)) / 2;
        $conflictingPairsCount = 0;

        $mods = Mod::whereIn('id', $modIds)->get(['id', 'name']);
        $names = $mods->pluck('name')->toArray();

        // Fetch conflicts by joining with mods table to match by names
        $dbConflicts = \DB::table('mod_conflicts')
            ->join('mods as m1', 'mod_conflicts.mod_id', '=', 'm1.id')
            ->join('mods as m2', 'mod_conflicts.conflicts_with_mod_id', '=', 'm2.id')
            ->whereIn('m1.name', $names)
            ->whereIn('m2.name', $names)
            ->select('mod_conflicts.*', 'm1.name as name1', 'm2.name as name2')
            ->get();

        // Map conflict rows back to the specific user-selected IDs
        $processedPairs = [];
        foreach ($dbConflicts as $c) {
            $m1Candidates = $mods->where('name', $c->name1)->pluck('id')->toArray();
            $m2Candidates = $mods->where('name', $c->name2)->pluck('id')->toArray();

            foreach ($m1Candidates as $id1) {
                foreach ($m2Candidates as $id2) {
                    $pairKey = min($id1, $id2) . '-' . max($id1, $id2);
                    if (in_array($pairKey, $processedPairs)) {
                        continue;
                    }
                    $processedPairs[] = $pairKey;
                    $conflictingPairsCount++;

                    $conflicts[] = [
                        'mod_id' => $id1,
                        'conflicts_with_mod_id' => $id2,
                        'reason_en' => $c->reason_en ?: 'Incompatible files.',
                        'reason_ar' => $c->reason_ar ?: 'تعارض في ملفات المود.',
                    ];
                }
            }
        }

        // Calculate Compatibility Score
        $score = 100;
        if ($totalPossiblePairs > 0) {
            $score = 100 - (($conflictingPairsCount / $totalPossiblePairs) * 100);
            $score = max(0, min(100, round($score)));
        }

        return response()->json([
            'conflicts' => $conflicts,
            'score' => $score
        ]);
    }

    /**
     * AJAX endpoint to suggest mod conflicts using AI (Gemini).
     */
    public function suggestConflicts(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'name_en' => 'required|string',
            'description_en' => 'nullable|string',
        ]);

        $gameId = $request->game_id;
        $name = $request->name_en;
        $description = $request->description_en ?? '';

        // Get other mods of this game
        $otherMods = Mod::where('game_id', $gameId)
            ->get(['id', 'name', 'description']);

        if ($otherMods->isEmpty()) {
            return response()->json([]);
        }

        $modList = '';
        foreach ($otherMods as $other) {
            $modList .= "- ID: {$other->id}, Name: {$other->name}, Description: " . substr($other->description, 0, 150) . "\n";
        }

        $prompt = "For the game with ID {$gameId}, we are adding a new mod:\n";
        $prompt .= "Name: {$name}\nDescription: {$description}\n\n";
        $prompt .= "We have the following existing mods in our database:\n{$modList}\n";
        $prompt .= "Analyze and suggest if there are any likely file conflicts, gameplay issues, or load order incompatibilities between this new mod and any of the existing mods. If yes, respond ONLY with a JSON array matching this schema (do not output any markdown wrapper or explanation, just the raw JSON array):\n";
        $prompt .= '[ { "mod_id": integer, "reason_en": "string", "reason_ar": "string" } ]' . "\n";
        $prompt .= "If there are no conflicts, return an empty JSON array: []\n";

        $ai = new \App\Services\AiProcessorService();
        $reflector = new \ReflectionClass($ai);
        $method = $reflector->getMethod('callGemini');
        $method->setAccessible(true);
        $result = $method->invoke($ai, $prompt);

        if (is_array($result)) {
            foreach ($result as &$res) {
                if (isset($res['mod_id'])) {
                    $m = Mod::find($res['mod_id']);
                    $res['mod_name'] = $m ? $m->name : ("Mod #" . $res['mod_id']);
                }
            }
        }

        return response()->json($result ?: []);
    }

    /**
     * Mod comparison board.
     */
    public function compare(Request $request)
    {
        $games = Game::orderBy('name', 'asc')->get();
        $selectedGameId = $request->get('game_id');
        $modIds = $request->get('mod_ids', []);

        $mods = [];
        if (!empty($modIds)) {
            $mods = Mod::whereIn('id', $modIds)
                ->with(['category'])
                ->get();
        }

        // Fetch conflicts between the selected mods
        $conflicts = [];
        if (count($modIds) > 1) {
            $dbConflicts = \DB::table('mod_conflicts')
                ->whereIn('mod_id', $modIds)
                ->whereIn('conflicts_with_mod_id', $modIds)
                ->get();

            foreach ($dbConflicts as $c) {
                $conflicts[] = [
                    'mod_id' => $c->mod_id,
                    'conflicts_with_mod_id' => $c->conflicts_with_mod_id,
                    'reason_en' => $c->reason_en,
                    'reason_ar' => $c->reason_ar,
                ];
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('mods.partials.comparison_table', compact('mods', 'conflicts'))->render(),
                'conflicts' => $conflicts
            ]);
        }

        return view('mods.compare', compact('games', 'mods', 'conflicts', 'selectedGameId'));
    }
}
