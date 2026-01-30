<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Deactive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval', 'admin_approval_notice')->first();

        $vendor = Auth::guard('vendor')->user();
        if (! $vendor) {
            return $next($request);
        }

        if ($vendor->status == 0) {
            if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                session()->flash('warning', $setting->admin_approval_notice);

                return redirect()->back();
            }
        }
        if ($vendor->email_verified_at == null && $setting->vendor_email_verification == 1) {
            Auth::guard('vendor')->logout();

            // avoid redirect loops + ensure old session is cleared
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('vendor.login')
                ->with('error', __('Please verify your email address.'));
        }

        return $next($request);
    }
}
