<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('staff_lang')) {
            app()->setLocale(session()->get('staff_lang'));
        } else {
            $defaultLang = Language::where('is_default', 1)->first();
            if (! empty($defaultLang)) {
                // Staff language values are stored like "admin_{code}" (see StaffController::languageChange),
                // so the default should follow the same format.
                $languageCode = 'admin_'.$defaultLang->code;
                app()->setLocale($languageCode);
                session()->put('staff_lang', $languageCode);
            }
        }

        return $next($request);
    }
}
