<?php

namespace App\Http\Middleware;

use App\Http\Helpers\VendorPermissionHelper;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('vendor')->check()) {
            $user = Auth::user();
            $package = VendorPermissionHelper::currentPackagePermission(Auth::guard('vendor')->user()->id);

            if (empty($package)) {
                session()->flash('warning', __('Please buy a package to use this panel!'));

                return redirect()->back();
            }
        }

        return $next($request);
    }
}
