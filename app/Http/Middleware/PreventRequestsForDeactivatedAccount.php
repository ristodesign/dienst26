<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class PreventRequestsForDeactivatedAccount
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userInfo = Auth::guard('web')->user();
        if (Session::get('secret_login') != 1) {
            if ($userInfo->status == 0) {
                Auth::guard('web')->logout();

                session()->flash('error', 'Sorry, your account has been deactivated!');

                return redirect()->route('user.login');
            }
        }

        return $next($request);
    }
}
