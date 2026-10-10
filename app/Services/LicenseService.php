<?php

namespace App\Services;

use App\Models\License;
use App\Models\Setting;
use Carbon\Carbon;

class LicenseService
{
    /**
     * Check if the current software license is active
     */
    public static function check(): array
    {
        $status = Setting::get('license_status', 'unlicensed');
        $type = Setting::get('license_type', 'none');
        $code = Setting::get('license_code', null);
        $expiresAtRaw = Setting::get('license_expires_at', null);

        $isLifetime = ($type === 'lifetime');

        if ($isLifetime) {
            return [
                'active' => true,
                'is_lifetime' => true,
                'type' => 'lifetime',
                'code' => $code,
                'expires_at' => null,
                'remaining_human' => 'লাইফটাইম অ্যাক্সেস (Lifetime)',
            ];
        }

        if (!$expiresAtRaw) {
            return [
                'active' => false,
                'is_lifetime' => false,
                'type' => $type,
                'code' => $code,
                'expires_at' => null,
                'remaining_human' => 'লাইসেন্স পাওয়া যায়নি (Unlicensed)',
            ];
        }

        $expiresAt = Carbon::parse($expiresAtRaw);
        $now = Carbon::now();

        if ($now->greaterThan($expiresAt)) {
            return [
                'active' => false,
                'is_lifetime' => false,
                'type' => $type,
                'code' => $code,
                'expires_at' => $expiresAt,
                'remaining_human' => 'সময়সীমা শেষ হয়েছে (Expired)',
            ];
        }

        $diffMinutes = $now->diffInMinutes($expiresAt);
        $diffHours = $now->diffInHours($expiresAt);
        $diffDays = $now->diffInDays($expiresAt);

        if ($diffMinutes < 60) {
            $remainingText = "{$diffMinutes} মিনিট বাকি";
        } elseif ($diffHours < 24) {
            $remainingText = "{$diffHours} ঘণ্টা বাকি";
        } else {
            $remainingText = "{$diffDays} দিন বাকি";
        }

        return [
            'active' => true,
            'is_lifetime' => false,
            'type' => $type,
            'code' => $code,
            'expires_at' => $expiresAt,
            'remaining_human' => $remainingText,
        ];
    }

    /**
     * Redeem/Activate a license code (One-time use only)
     */
    public static function activate(string $code, ?string $userEmail = null): array
    {
        $code = trim(strtoupper($code));
        $license = License::where('code', $code)->first();

        if (!$license) {
            throw new \Exception('প্রদত্ত লাইসেন্স কোডটি সঠিক নয়! অনুগ্রহ করে সঠিক কোড দিন।');
        }

        if ($license->status !== 'unused') {
            throw new \Exception('এই লাইসেন্স কোডটি ইতিমধ্যে ব্যবহৃত হয়েছে! একটি লাইসেন্স কোড কেবল একবারই ব্যবহারযোগ্য।');
        }

        $now = Carbon::now();
        $expiresAt = null;

        if ($license->type === 'lifetime') {
            $expiresAt = null;
        } elseif ($license->type === 'trial_30m') {
            $expiresAt = $now->copy()->addMinutes(30);
        } elseif ($license->type === 'trial_1h') {
            $expiresAt = $now->copy()->addHours(1);
        } elseif ($license->duration_minutes) {
            $expiresAt = $now->copy()->addMinutes($license->duration_minutes);
        } else {
            $expiresAt = $now->copy()->addHours(24);
        }

        // Mark as used immediately (Single-use enforcement)
        $license->update([
            'status' => 'used',
            'used_at' => $now,
            'used_by_email' => $userEmail,
            'expires_at' => $expiresAt,
        ]);

        // Save active system license settings
        Setting::updateOrCreate(['key' => 'license_status'], ['value' => 'active']);
        Setting::updateOrCreate(['key' => 'license_type'], ['value' => $license->type]);
        Setting::updateOrCreate(['key' => 'license_code'], ['value' => $license->code]);
        Setting::updateOrCreate(['key' => 'license_expires_at'], ['value' => $expiresAt ? $expiresAt->toDateTimeString() : null]);
        Setting::updateOrCreate(['key' => 'license_activated_at'], ['value' => $now->toDateTimeString()]);

        return [
            'license' => $license,
            'expires_at' => $expiresAt,
        ];
    }
}
