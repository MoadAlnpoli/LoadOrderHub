<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Copy brand logo and favicon if present in brain artifacts
        try {
            $brandLogoSource = 'C:/Users/HP/.gemini/antigravity-ide/brain/2501c851-5dbf-4956-a848-35482d70502d/media__1785378010849.jpg';
            if (file_exists($brandLogoSource)) {
                $imgDir = public_path('images');
                if (!file_exists($imgDir)) {
                    @mkdir($imgDir, 0777, true);
                }
                @copy($brandLogoSource, public_path('images/logo.png'));
                @copy($brandLogoSource, public_path('images/favicon.png'));
                @copy($brandLogoSource, public_path('favicon.ico'));
            }
        } catch (\Throwable $logoErr) {
            // Ignore
        }

        if (config('app.env') === 'production' || app()->environment('production')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }

        // Gracefully fallback to sqlite if primary mysql host is unreachable / DNS fails
        if (config('database.default') === 'mysql') {
            try {
                \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
            } catch (\Throwable $e) {
                $sqlitePath = database_path('database.sqlite');
                if (!file_exists($sqlitePath)) {
                    @touch($sqlitePath);
                }
                config([
                    'database.default' => 'sqlite',
                    'database.connections.sqlite.database' => $sqlitePath,
                    'session.driver' => 'file',
                ]);
                \Illuminate\Support\Facades\DB::purge();

                // Auto-migrate & seed sqlite database if users table or ad_slots table is missing
                try {
                    if (!\Illuminate\Support\Facades\Schema::hasTable('users') || !\Illuminate\Support\Facades\Schema::hasTable('ad_slots')) {
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    }
                } catch (\Throwable $migErr) {
                    // Ignore migration errors
                }
            }
        }

        // Ensure main admin account exists and auto-seed if database is empty
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                $adm = \App\Models\User::firstOrCreate(
                    ['email' => 'moadnp@gmail.com'],
                    [
                        'name' => 'Moad Admin',
                        'password' => \Illuminate\Support\Facades\Hash::make('moad1234'),
                        'is_admin' => true,
                    ]
                );
                if (!$adm->is_admin) {
                    $adm->update(['is_admin' => true]);
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('games') && \App\Models\Game::count() === 0) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Throwable $adminErr) {
            // Ignore
        }

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                $globalAds = \App\Models\AdSlot::where('is_active', true)->get();
                $view->with('globalAds', $globalAds);
            } catch (\Exception $e) {
                $view->with('globalAds', collect());
            }

            try {
                $pendingReportsCount = \App\Models\ModReport::where('status', 'active')->count();
                $view->with('pendingReportsCount', $pendingReportsCount);
            } catch (\Exception $e) {
                $view->with('pendingReportsCount', 0);
            }
        });
    }
}
