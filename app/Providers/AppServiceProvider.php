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

                // Auto-migrate & seed sqlite database if users table is missing
                try {
                    if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    }
                } catch (\Throwable $migErr) {
                    // Ignore migration errors
                }
            }
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
