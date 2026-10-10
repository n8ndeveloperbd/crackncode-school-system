<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LicenseService;

class LicenseController extends Controller
{
    /**
     * Show License Activation / Renewal Page
     */
    public function showActivationForm()
    {
        $licenseInfo = LicenseService::check();
        return view('license.activate', compact('licenseInfo'));
    }

    /**
     * Handle license activation request
     */
    public function activate(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ], [
            'code.required' => 'অনুগ্রহ করে লাইসেন্স কোডটি লিখুন।',
        ]);

        try {
            $userEmail = auth()->check() ? auth()->user()->email : null;
            $result = LicenseService::activate($request->code, $userEmail);

            $expiresText = $result['expires_at']
                ? $result['expires_at']->format('d M Y, h:i A') . ' পর্যন্ত'
                : 'আজীবন (Lifetime)';

            return redirect()->route('dashboard')
                ->with('success', "🎉 অভিনন্দন! লাইসেন্স সফলভাবে অ্যাক্টিভেট করা হয়েছে। মেয়াদ: {$expiresText}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
