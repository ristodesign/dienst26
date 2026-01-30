<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Models\Language;
use Closure;
use Illuminate\Http\Request;

class VendorLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('vendor_lang')) {
            app()->setLocale(session()->get('vendor_lang'));
        } else {
            // set the default language as system locale
            $defaultLang = Language::where('is_default', 1)->first();
            if (! empty($defaultLang)) {
                $languageCode = 'admin_'.$defaultLang->code;
                app()->setLocale($languageCode);
                // Set session so AppServiceProvider can find it
                session()->put('vendor_lang', $languageCode);
            }
        }

        return $next($request);
    }
}
