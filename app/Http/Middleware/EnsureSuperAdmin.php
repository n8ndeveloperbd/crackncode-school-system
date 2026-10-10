<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'অনুমোদনহীন অ্যাক্সেস! এই পেজে প্রবেশাধিকার কেবল সুপার অ্যাডমিনের জন্য সংরক্ষিত।');
        }

        return $next($request);
    }
}
