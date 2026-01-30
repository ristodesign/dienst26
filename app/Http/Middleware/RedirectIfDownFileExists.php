<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Http\Request;

class RedirectIfDownFileExists
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
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
