<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Closure;
use DB;
use Illuminate\Http\Request;

class ShopStatusCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $info = DB::table('basic_settings')->select('shop_status')->first();
        if ($info->shop_status != 1) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
