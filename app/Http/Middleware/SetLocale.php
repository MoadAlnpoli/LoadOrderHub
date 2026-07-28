<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin routes are forced to Arabic, visitor routes can change language
        if ($request->is('admin') || $request->is('admin/*')) {
            app()->setLocale('ar');
        } else {
            $supportedLocales = ['en', 'ar', 'es', 'de', 'fr'];
            
            if ($request->has('lang')) {
                $lang = $request->query('lang');
                if (in_array($lang, $supportedLocales)) {
                    session(['locale' => $lang]);
                }
            }

            $locale = session('locale', 'en');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
