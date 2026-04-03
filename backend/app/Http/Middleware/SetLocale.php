<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $next_request, Closure $next): Response
    {
        $locale = $next_request->header('Accept-Language');

        // Extract first match if comma-separated
        if ($locale) {
            $locales = explode(',', $locale);
            $locale = trim(strtolower($locales[0]));
        }

        // Only allow en, ar, fr
        if (in_array($locale, ['en', 'ar', 'fr'])) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.fallback_locale', 'en'));
        }

        return $next($next_request);
    }
}
