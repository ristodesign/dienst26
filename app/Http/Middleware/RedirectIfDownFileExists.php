<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfDownFileExists
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (file_exists(base_path('/storage/framework/down'))) {
            return $next($request);
        } else {
            return redirect()->route('index');
        }
    }
}
