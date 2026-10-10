<?php

namespace App\Services;

use App\Models\License;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

class LicenseService
{
    /**
     * Check if the license is active for a given user or system
     */
    public static function check(?User $user = null): array
    {
        // If user is authenticated
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }

        // 1. Super Admin always has master unlimited access
        if ($user && $user->isSuperAdmin()) {
            return [
                'active' => true,
                'is_valid' => true,
                'is_lifetime' => true,
                'type' => 'master',
                'code' => 'MASTER-SUPER-ADMIN',
                'expires_at' => null,
                'remaining_human' => 'সুপার অ্যাডমিন (আনলিমিটেড)',
            ];
        }

        // 2. If a specific user account is provided, check their per-account license
        if ($user) {
            if ($user->license_type === 'lifetime') {
                return [
                    'active' => true,
                    'is_valid' => true,
                    'is_lifetime' => true,
                    'type' => 'lifetime',
                    'code' => $user->license_code,
                    'expires_at' => null,
                    'remaining_human' => 'আজীবন লাইসেন্স (Lifetime)',
                ];
            }

            if (!$user->license_code || !$user->license_expires_at) {
                return [
                    'active' => false,
                    'is_valid' => false,
                    'is_lifetime' => false,
                    'type' => 'unlicensed',
                    'code' => null,
                    'expires_at' => null,
                    'remaining_human' => 'লাইসেন্সহীন (Unlicensed)',
                ];
            }

            $expiresAt = $user->license_expires_at;
            $now = Carbon::now();

            if ($now->greaterThan($expiresAt)) {
                return [
                    'active' => false,
                    'is_valid' => false,
                    'is_lifetime' => false,
                    'type' => $user->license_type,
                    'code' => $user->license_code,
                    'expires_at' => $expiresAt,
                    'remaining_human' => 'সময়সীমা শেষ হয়েছে (Expired)',
                ];
            }

            $diffMinutes = (int) $now->diffInMinutes($expiresAt);
            $diffHours = (int) $now->diffInHours($expiresAt);
            $diffDays = (int) ceil($now->diffInHours($expiresAt) / 24);

            if ($diffMinutes < 60) {
                $remainingText = "{$diffMinutes} মিনিট বাকি";
            } elseif ($diffHours < 24) {
                $remainingText = "{$diffHours} ঘণ্টা বাকি";
            } else {
                $remainingText = "{$diffDays} দিন বাকি";
            }

            return [
                'active' => true,
                'is_valid' => true,
                'is_lifetime' => false,
                'type' => $user->license_type,
                'code' => $user->license_code,
                'expires_at' => $expiresAt,
                'remaining_human' => $remainingText,
            ];
        }

        // Fallback to system settings if no user context
        $type = Setting::get('license_type', 'none');
        $code = Setting::get('license_code', null);
        $expiresAtRaw = Setting::get('license_expires_at', null);

        if ($type === 'lifetime') {
            return [
                'active' => true,
                'is_valid' => true,
                'is_lifetime' => true,
                'type' => 'lifetime',
                'code' => $code,
                'expires_at' => null,
                'remaining_human' => 'আজীবন লাইসেন্স (Lifetime)',
            ];
        }

        if (!$expiresAtRaw) {
            return [
                'active' => false,
                'is_valid' => false,
                'is_lifetime' => false,
                'type' => $type,
                'code' => $code,
                'expires_at' => null,
                'remaining_human' => 'লাইসেন্সহীন (Unlicensed)',
            ];
        }

        $expiresAt = Carbon::parse($expiresAtRaw);
        $now = Carbon::now();

        if ($now->greaterThan($expiresAt)) {
            return [
                'active' => false,
                'is_valid' => false,
                'is_lifetime' => false,
                'type' => $type,
                'code' => $code,
                'expires_at' => $expiresAt,
                'remaining_human' => 'সময়সীমা শেষ হয়েছে (Expired)',
            ];
        }

        return [
            'active' => true,
            'is_valid' => true,
            'is_lifetime' => false,
            'type' => $type,
            'code' => $code,
            'expires_at' => $expiresAt,
            'remaining_human' => 'সক্রিয় লাইসেন্স',
        ];
    }

    /**
     * Redeem/Activate a license code for a user (One-time use only)
     */
    public static function activate(string $code, ?User $user = null): array
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

        // Mark license as used (Single-use enforcement)
        $license->update([
            'status' => 'used',
            'used_at' => $now,
            'used_by_email' => $user ? $user->email : 'general',
            'expires_at' => $expiresAt,
        ]);

        // If user is authenticated, attach the license directly to this account
        if ($user) {
            $user->update([
                'license_code' => $license->code,
                'license_type' => $license->type,
                'license_expires_at' => $expiresAt,
                'license_activated_at' => $now,
            ]);
        }

        // Also update settings table as system reference
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
