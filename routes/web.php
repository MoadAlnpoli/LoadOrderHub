<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ModPackController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CompareController;
use Illuminate\Support\Facades\Route;

// Main portal list of games
Route::get('/', [GameController::class, 'index'])->name('home');

// Legal & Info Pages
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/about', 'pages.about')->name('about');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/changelog', 'pages.changelog')->name('changelog');
Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact', function (Illuminate\Http\Request $request) {
    $request->validate(['name' => 'required', 'email' => 'required|email', 'message' => 'required']);
    return back()->with('success', 'Message sent successfully! We will get back to you soon.');
})->name('contact.post');

// XML Dynamic Sitemap & Robots.txt
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Mods explorer catalog route
Route::get('/mods-explorer', [ModController::class, 'explorer'])->name('mods.explorer');

// Trending mods page
Route::get('/mods/trending', [ModController::class, 'trending'])->name('mods.trending');

// Top Mods Weekly page
Route::get('/mods/top-weekly', [ModController::class, 'topWeekly'])->name('mods.top-weekly');

// Compare mods page
Route::get('/mods/compare', [CompareController::class, 'index'])->name('mods.compare');

// Global search API
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');


Route::get('/games/{game:slug}', [GameController::class, 'show'])->name('games.show');

// Game Mods listing page
Route::get('/games/{game:slug}/mods', [ModController::class, 'index'])->name('games.mods');

// Link redirect ad page
Route::get('/redirect', [GameController::class, 'redirectLink'])->name('link.redirect');

// Mod details view
Route::get('/mods/{mod:slug}', [ModController::class, 'show'])->name('mods.show');
Route::get('/api/mods/{mod:slug}/quick-view', [ModController::class, 'quickView'])->name('api.mods.quickView');

// Mod comment post
Route::post('/mods/{mod}/comments', [ModController::class, 'storeComment'])->name('mods.comments.store');

Route::get('/modpacks', [ModPackController::class, 'index'])->name('modpacks.index');
Route::get('/modpacks/create', [ModPackController::class, 'create'])->name('modpacks.create')->middleware('auth');
Route::post('/modpacks', [ModPackController::class, 'store'])->name('modpacks.store')->middleware('auth');
Route::get('/modpacks/{modPack}', [ModPackController::class, 'show'])->name('modpacks.show');
Route::get('/embed/pack/{modPack:slug}', [ModPackController::class, 'embed'])->name('modpacks.embed');

// Export load order as text file / JSON / MO2
Route::get('/modpacks/{modPack}/export-markdown', [ModPackController::class, 'exportMarkdown'])->name('modpacks.export-markdown');
Route::get('/modpacks/{modPack}/export-mo2', [ModPackController::class, 'exportMo2'])->name('modpacks.export-mo2');

// Add comment/reply
Route::post('/modpacks/{modPack}/comments', [CommentController::class, 'store'])->name('comments.store');

// Vote (upvote/downvote)
Route::post('/modpacks/{modPack}/rate', [RatingController::class, 'rate'])->name('modpacks.rate');

// --- Authentication Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot/Reset Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- User Profile & Bookmarking Routes ---
Route::middleware('auth')->group(function () {
    Route::post('/mods/{mod}/report', [App\Http\Controllers\ModReportController::class, 'store'])->name('mods.report')->middleware('auth');
    Route::post('/modpacks/{pack}/rate', [App\Http\Controllers\PackRatingController::class, 'store'])->name('modpacks.rate')->middleware('auth');

    Route::get('/profile/{user?}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update')->middleware('auth');

    Route::post('/modpacks/{modPack}/save', [ModPackController::class, 'toggleSave'])->name('modpacks.save')->middleware('auth');

    Route::get('/admin/review-queue', [App\Http\Controllers\AdminController::class, 'reviewQueue'])->name('admin.review-queue')->middleware('auth');
    Route::post('/admin/mods/{mod}/approve', [App\Http\Controllers\AdminController::class, 'approveMod'])->name('admin.mods.approve')->middleware('auth');
    Route::post('/admin/mods/{mod}/reject', [App\Http\Controllers\AdminController::class, 'rejectMod'])->name('admin.mods.reject')->middleware('auth');
});

// --- Admin Panel Routes ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/enrich-mods', [AdminController::class, 'enrichMods'])->name('enrich');
    Route::post('/trigger-scraper', [AdminController::class, 'triggerScraper'])->name('scraper');
    Route::post('/fix-missing-images', [AdminController::class, 'fixMissingImages'])->name('fix-missing-images');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('games.store');
    Route::put('/games/{game}', [AdminController::class, 'updateGame'])->name('games.update');
    Route::delete('/games/{game}', [AdminController::class, 'deleteGame'])->name('games.delete');
    Route::put('/modpacks/{modPack}', [AdminController::class, 'updateModPack'])->name('modpacks.update');
    Route::delete('/modpacks/{modPack}', [AdminController::class, 'deleteModPack'])->name('modpacks.delete');
    Route::post('/modpacks/{modPack}/publish', [AdminController::class, 'publishModPack'])->name('modpacks.publish');
    Route::put('/mods/{mod}', [AdminController::class, 'updateMod'])->name('mods.update');
    Route::delete('/mods/{mod}', [AdminController::class, 'deleteMod'])->name('mods.delete');
    Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('comments.delete');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/scrape', [AdminController::class, 'triggerScraper'])->name('scrape');
    Route::get('/ai/search-videos', [AdminController::class, 'searchVideos'])->name('ai.search');
    Route::get('/ai/extract-metadata', [AdminController::class, 'extractMetadata'])->name('ai.extract');
    Route::get('/ai/get-mod-details', [AdminController::class, 'getModDetails'])->name('ai.mod-details');
    Route::post('/ai/save-imported-video', [AdminController::class, 'saveImportedVideo'])->name('ai.save-import');

    // Direct Nexus Search & Quick Add
    Route::get('/nexus/search', [AdminController::class, 'searchNexus'])->name('nexus.search');
    Route::post('/mods/quick-add', [AdminController::class, 'quickAddMod'])->name('mods.quick-add');
    Route::post('/mods/translate-old', [AdminController::class, 'enrichMods'])->name('mods.translate-old'); // Fallback map to enrichMods

    // Sync individual mod from Nexus Mods API
    Route::post('/mods/{mod}/sync-nexus', function (\App\Models\Mod $mod, \App\Services\NexusModsService $nexus) {
        if (!auth()->user()->is_admin) abort(403);
        $success = $nexus->syncModFromNexus($mod);
        if ($success) {
            return back()->with('success', 'تم تحديث جلب البيانات والصور من Nexus Mods بنجاح!');
        }
        return back()->with('error', 'تعذر جلب البيانات من Nexus Mods. يرجى التأكد من وجود رابط Nexus صحيح أو مفتاح API.');
    })->name('mods.sync-nexus');

    // Fix all mods with unknown versions automatically
    Route::post('/mods/fix-unknown-versions', function () {
        if (!auth()->user()->is_admin) abort(403);
        $mods = \App\Models\Mod::doesntHave('gameVersions')->with('game.versions')->get();
        $fixed = 0;
        foreach ($mods as $mod) {
            if ($mod->game && $mod->game->versions->isNotEmpty()) {
                $mod->gameVersions()->sync($mod->game->versions->pluck('id'));
                $fixed++;
            }
        }
        return back()->with('success', "تم تعيين الإصدارات المتوافقة لعدد {$fixed} مود بنجاح!");
    })->name('mods.fix-unknown-versions');

    // Translate old mods lacking descriptions
    Route::post('/mods/translate-old', [AdminController::class, 'translateOldMods'])->name('mods.translate-old');

    // Flag mod as problematic / remove flag
    Route::post('/mods/{mod}/flag', [AdminController::class, 'flagMod'])->name('mods.flag');

    // Ads Management
    Route::get('/ads', [AdminController::class, 'ads'])->name('ads.index');
    Route::post('/ads/toggle', [AdminController::class, 'toggleAd'])->name('ads.toggle');
    Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('export.csv');
    Route::post('/maintenance/toggle', [AdminController::class, 'toggleMaintenance'])->name('maintenance.toggle');
    Route::post('/ads', [AdminController::class, 'storeAd'])->name('ads.store');
    Route::put('/ads/{ad}', [AdminController::class, 'updateAd'])->name('ads.update');
    Route::delete('/ads/{ad}', [AdminController::class, 'destroyAd'])->name('ads.delete');

    // Register mod conflict (incompatibility between two mods)
    Route::post('/mods/conflicts/add', [AdminController::class, 'addModConflict'])->name('mods.conflicts.add');

    // Add mod dependency
    Route::post('/mods/dependencies/add', [AdminController::class, 'addModDependency'])->name('mods.dependencies.add');

    // Manual mod entry & conflicts
    Route::get('/mods/create', [ModController::class, 'create'])->name('mods.create');
    Route::post('/mods/store', [ModController::class, 'store'])->name('mods.store');
    Route::get('/mods/search-by-game', [ModController::class, 'searchModsByGame'])->name('mods.search-by-game');
    Route::post('/mods/suggest-conflicts', [ModController::class, 'suggestConflicts'])->name('mods.suggest-conflicts');
    Route::post('/mods/check-conflicts', [ModController::class, 'checkConflicts'])->name('mods.check-conflicts');
});

// Mod comparison route (public)
Route::get('/mods/compare', [ModController::class, 'compare'])->name('mods.compare');

// Temporary route for dynamic migrations
Route::get('/setup-phase56', function () {
    // 1. Add is_verified to mods
    if (!\Illuminate\Support\Facades\Schema::hasColumn('mods', 'is_verified')) {
        \Illuminate\Support\Facades\Schema::table('mods', function ($table) {
            $table->boolean('is_verified')->default(false);
        });
    }

    // 2. Create ad_clicks table
    if (!\Illuminate\Support\Facades\Schema::hasTable('ad_clicks')) {
        \Illuminate\Support\Facades\Schema::create('ad_clicks', function ($table) {
            $table->id();
            $table->string('ad_name');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    return "Phase 5 & 6 Migrations Completed!";
});

// Ad Click Tracking Route
Route::post('/track-ad-click', function (\Illuminate\Http\Request $request) {
    if (\Illuminate\Support\Facades\Schema::hasTable('ad_clicks')) {
        \Illuminate\Support\Facades\DB::table('ad_clicks')->insert([
            'ad_name' => $request->input('ad_name', 'unknown_ad'),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    return response()->json(['status' => 'success']);
})->name('ads.track');

// Newsletter Subscription Route
Route::post('/newsletter/subscribe', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    if (\Illuminate\Support\Facades\Schema::hasTable('newsletter_subscribers')) {
        \App\Models\NewsletterSubscriber::firstOrCreate(['email' => $request->email]);
    } else {
        // Fallback if table doesn't exist yet
        \Illuminate\Support\Facades\Schema::create('newsletter_subscribers', function ($table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
        \App\Models\NewsletterSubscriber::firstOrCreate(['email' => $request->email]);
    }
    return back()->with('success', app()->getLocale() == 'ar' ? 'تم الاشتراك في النشرة البريدية بنجاح!' : 'Successfully subscribed to the newsletter!');
})->name('newsletter.subscribe');

// Temporary routes to manage migrations programmatically
Route::get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    } catch (\Throwable $e) {
        return response("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString(), 200);
    }
});

Route::get('/run-sql', function () {
    try {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('mods', 'file_size_kb')) {
            \Illuminate\Support\Facades\Schema::table('mods', function ($table) {
                $table->integer('file_size_kb')->nullable()->after('version');
            });
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('mod_packs', 'is_private')) {
            \Illuminate\Support\Facades\Schema::table('mod_packs', function ($table) {
                $table->boolean('is_private')->default(false)->after('is_published');
            });
        }
        return "SQL run success!";
    } catch (\Throwable $e) {
        return "Error: " . $e->getMessage();
    }
});


Route::get('/fix-migrations', function () {
    try {
        $migrations = [
            '2026_07_15_000003_create_categories_table',
            '2026_07_15_000004_create_video_staging_table',
            '2026_07_15_000005_update_mod_conflicts_reasons',
            '2026_07_15_000006_create_extraction_logs_table',
            '2026_07_15_000007_add_slug_to_games_table'
        ];
        \DB::table('migrations')->whereIn('migration', $migrations)->delete();
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return response("Migrations fixed and completed successfully:\n" . \Illuminate\Support\Facades\Artisan::output());
    } catch (\Throwable $e) {
        return response("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString(), 200);
    }
});

// ─── Newsletter Routes ──────────────────────────────────────────────────────
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// ─── Ad Click Tracking ──────────────────────────────────────────────────────
Route::get('/ads/{ad}/click', function (\App\Models\AdSlot $ad) {
    $ad->increment('clicks');
    $url = request('redirect', '/');
    return redirect($url);
})->name('ads.click');

Route::post('/ads/{ad}/impression', function (\App\Models\AdSlot $ad) {
    $ad->increment('impressions');
    return response()->json(['ok' => true]);
})->name('ads.impression');

// ─── Top Mods Weekly Page ────────────────────────────────────────────────────
Route::get('/top-mods', function () {
    $topMods = \App\Models\Mod::where('status', 'published')
        ->with('game')
        ->orderBy('downloads_count', 'desc')
        ->take(20)
        ->get();
    return view('mods.top-weekly', compact('topMods'));
})->name('mods.top-weekly');

// ─── Nexus Import (Admin) ─────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/nexus/import', function (\Illuminate\Http\Request $request) {
        if (!auth()->user()->is_admin) abort(403);
        $request->validate([
            'nexus_url' => 'required|url',
            'game_id'   => 'required|exists:games,id',
        ]);

        // Parse Nexus URL: https://www.nexusmods.com/{game}/{mods}/{id}
        preg_match('/nexusmods\.com\/([^\/]+)\/mods\/(\d+)/i', $request->nexus_url, $m);
        if (!isset($m[1], $m[2])) {
            return back()->withErrors(['nexus_url' => 'Invalid Nexus Mods URL format.']);
        }

        \App\Jobs\ImportNexusModJob::dispatch($m[1], (int)$m[2], (int)$request->game_id);

        return back()->with('success', 'Nexus mod import queued! It will appear shortly.');
    })->name('admin.nexus.import');

    Route::post('/admin/nexus/sync-all', function () {
        if (!auth()->user()->is_admin) abort(403);
        \App\Jobs\SyncNexusModsJob::dispatch();
        return back()->with('success', 'Full sync queued!');
    })->name('admin.nexus.sync');

    Route::post('/admin/nexus/import-game', function (\Illuminate\Http\Request $request) {
        if (!auth()->user()->is_admin) abort(403);
        $request->validate(['game_id' => 'required|exists:games,id']);
        \App\Jobs\AutoImportNexusModsJob::dispatch('endorsements', (int)$request->game_id);
        return back()->with('success', 'تم بدء استيراد أفضل المودات لهذه اللعبة بنجاح. ستظهر قريباً!');
    })->name('admin.nexus.import-game');
});
