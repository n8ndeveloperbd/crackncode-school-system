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
        // 1. If not authenticated, allow request (auth middleware handles login requirement)
        if (!auth()->check()) {
            return $next($request);
        }

        // 2. Super Admin always has full master access
        if (auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        // 3. Allow license activation routes and logout
        if ($request->routeIs('license.activate', 'license.submit', 'logout')) {
            return $next($request);
        }

        // 4. Verify user's specific account license
        $check = LicenseService::check(auth()->user());

        if (!$check['active']) {
            $message = empty(auth()->user()->license_code)
                ? 'আপনার অ্যাকাউন্টে সক্রিয় কোনো লাইসেন্স কোড নেই। সফটওয়্যারে প্রবেশের জন্য অনুগ্রহ করে একটি বৈধ লাইসেন্স কোড দিন।'
                : 'আপনার অ্যাকাউন্টের লাইসেন্সের মেয়াদ শেষ হয়েছে! পুনরায় ব্যবহারের জন্য একটি নতুন লাইসেন্স কোড প্রবেশ করান।';

            return redirect()->route('license.activate')->with('error', $message);
        }

        return $next($request);
    }
}
