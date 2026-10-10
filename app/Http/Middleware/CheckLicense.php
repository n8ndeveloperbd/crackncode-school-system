<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LicenseService;

class CheckLicense
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Super Admin always has full access
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        // 2. Allow license activation routes and logout
        if ($request->routeIs('license.activate', 'license.submit', 'logout')) {
            return $next($request);
        }

        // 3. Verify software license validity
        $check = LicenseService::check();

        if (!$check['active']) {
            return redirect()->route('license.activate')
                ->with('error', 'আপনার সফটওয়্যারের ট্রায়াল বা লাইসেন্সের মেয়াদ শেষ হয়েছে! পুনরায় ব্যবহারের জন্য একটি বৈধ লাইসেন্স কোড প্রবেশ করান।');
        }

        return $next($request);
    }
}
