<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\License;
use App\Models\Setting;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    /**
     * Super Admin Dashboard Overview
     */
    public function index()
    {
        $totalLicenses = License::count();
        $unusedLicenses = License::where('status', 'unused')->count();
        $usedLicenses = License::where('status', 'used')->count();
        $principalsCount = User::whereIn('role', ['principal', 'admin'])->count();
        $cashiersCount = User::whereIn('role', ['cashier', 'accounts'])->count();

        $licenseInfo = LicenseService::check();
        $recentLicenses = License::latest()->take(8)->get();
        $staffUsers = User::where('role', '!=', 'super_admin')->latest()->take(6)->get();

        return view('superadmin.index', compact(
            'totalLicenses',
            'unusedLicenses',
            'usedLicenses',
            'principalsCount',
            'cashiersCount',
            'licenseInfo',
            'recentLicenses',
            'staffUsers'
        ));
    }

    /**
     * Manage Licenses
     */
    public function licenses(Request $request)
    {
        $status = $request->query('status');

        $query = License::latest();
        if ($status && in_array($status, ['unused', 'used', 'revoked'])) {
            $query->where('status', $status);
        }

        $licenses = $query->paginate(15)->withQueryString();
        $licenseInfo = LicenseService::check();

        return view('superadmin.licenses', compact('licenses', 'licenseInfo', 'status'));
    }

    /**
     * Generate a new license code
     */
    public function generateLicense(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'in:trial_30m,trial_1h,custom_minutes,custom_days,lifetime'],
            'client_name' => ['nullable', 'string', 'max:100'],
            'custom_duration' => ['nullable', 'integer', 'min:1'],
        ]);

        $type = $request->type;
        $durationMinutes = null;

        if ($type === 'trial_30m') {
            $durationMinutes = 30;
        } elseif ($type === 'trial_1h') {
            $durationMinutes = 60;
        } elseif ($type === 'custom_minutes') {
            $durationMinutes = (int) $request->custom_duration;
        } elseif ($type === 'custom_days') {
            $durationMinutes = ((int) $request->custom_duration) * 1440;
        } elseif ($type === 'lifetime') {
            $durationMinutes = null;
        }

        $code = License::generateCode();

        $license = License::create([
            'code' => $code,
            'type' => $type,
            'duration_minutes' => $durationMinutes,
            'client_name' => $request->client_name ?: 'General Client',
            'status' => 'unused',
        ]);

        return back()->with('success', "🎉 লাইসেন্স কোড তৈরি সম্পন্ন: {$license->code} ({$license->type_label})");
    }

    /**
     * Revoke or delete a license
     */
    public function deleteLicense(License $license)
    {
        $code = $license->code;
        $license->delete();

        return back()->with('success', "লাইসেন্স {$code} সফলভাবে মুছে ফেলা হয়েছে।");
    }

    /**
     * Staff Users Management (Principals and Cashiers)
     */
    public function users()
    {
        $users = User::where('role', '!=', 'super_admin')->latest()->paginate(15);
        return view('superadmin.users', compact('users'));
    }

    /**
     * Manually create a Principal or Cashier account
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:principal,cashier'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.unique' => 'এই ইমেইল ঠিকানাটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
            'password.min' => 'পাসওয়ার্ড ন্যূনতম ৬ অক্ষরের হতে হবে।',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => 'active',
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', "✅ {$user->role_label} অ্যাকাউন্ট সফলভাবে তৈরি করা হয়েছে!");
    }

    /**
     * Update an existing staff user
     */
    public function updateUser(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:principal,cashier'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'ব্যবহারকারীর তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Toggle status (active/inactive)
     */
    public function toggleUserStatus(User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $newStatus = ($user->status === 'active') ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $statusText = ($newStatus === 'active') ? 'সক্রিয়' : 'নিষ্ক্রিয়';
        return back()->with('success', "ব্যবহারকারী একাউন্ট সফলভাবে {$statusText} করা হয়েছে।");
    }

    /**
     * Delete a staff user
     */
    public function deleteUser(User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $user->delete();
        return back()->with('success', 'ব্যবহারকারী একাউন্ট সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
