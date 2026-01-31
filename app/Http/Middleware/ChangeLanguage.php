<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ChangeLanguage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('currentLocaleCode');

        if (empty($locale)) {
            // set the default language as system locale
            $languageCode = Language::query()
                ->where('is_default', '=', 1)
                ->pluck('code')
                ->first();

            if (! empty($languageCode)) {
                // persist so the whole site consistently uses the default language
                $request->session()->put('currentLocaleCode', $languageCode);
                App::setLocale($languageCode);
            }
        } else {
            // set the selected language as system locale
            App::setLocale($locale);
        }

        return $next($request);
    }
}
