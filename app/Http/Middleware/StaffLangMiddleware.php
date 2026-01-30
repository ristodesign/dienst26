<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Models\Language;
use Closure;
use Illuminate\Http\Request;

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
                app()->setLocale($defaultLang->code);
            }
        }

        return $next($request);
    }
}
