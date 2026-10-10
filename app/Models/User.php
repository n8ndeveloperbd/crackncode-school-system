<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'license_code',
        'license_type',
        'license_expires_at',
        'license_activated_at',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isPrincipal(): bool
    {
        return in_array($this->role, ['principal', 'admin']);
    }

    public function isCashier(): bool
    {
        return in_array($this->role, ['cashier', 'accounts']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'সুপার অ্যাডমিন (Super Admin)',
            'principal', 'admin' => 'অধ্যক্ষ / প্রধান শিক্ষক (Principal)',
            'cashier', 'accounts' => 'হিসাব রক্ষক (Cashier)',
            default => ucfirst($this->role),
        };
    }

    public function hasActiveLicense(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->license_type === 'lifetime') {
            return true;
        }

        if ($this->license_expires_at && now()->lessThan($this->license_expires_at)) {
            return true;
        }

        return false;
    }

    public function getLicenseRemainingHumanAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'আনলিমিটেড (Master)';
        }

        if ($this->license_type === 'lifetime') {
            return 'আজীবন লাইসেন্স (Lifetime)';
        }

        if (!$this->license_expires_at) {
            return 'লাইসেন্সহীন (Unlicensed)';
        }

        $now = now();
        if ($now->greaterThan($this->license_expires_at)) {
            return 'মেয়াদ শেষ (Expired)';
        }

        $diffMinutes = (int) $now->diffInMinutes($this->license_expires_at);
        $diffHours = (int) $now->diffInHours($this->license_expires_at);
        $diffDays = (int) ceil($now->diffInHours($this->license_expires_at) / 24);

        if ($diffMinutes < 60) {
            return "{$diffMinutes} মিনিট বাকি";
        } elseif ($diffHours < 24) {
            return "{$diffHours} ঘণ্টা বাকি";
        } else {
            return "{$diffDays} দিন বাকি";
        }
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'license_expires_at' => 'datetime',
            'license_activated_at' => 'datetime',
        ];
    }
}
