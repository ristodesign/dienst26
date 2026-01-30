<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ChangeLanguage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('currentLocaleCode')) {
            $locale = $request->session()->get('currentLocaleCode');
        }

        if (empty($locale)) {
            // set the default language as system locale
            $languageCode = Language::query()->where('is_default', '=', 1)
                ->pluck('code')
                ->first();

            App::setLocale($languageCode);
        } else {
            // set the selected language as system locale
            App::setLocale($locale);
        }

        return $next($request);
    }
}
