<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
