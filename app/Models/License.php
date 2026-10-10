<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'duration_minutes',
        'client_name',
        'status',
        'used_at',
        'used_by_email',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Generate a unique license code in CNC-XXXX-XXXX-XXXX format
     */
    public static function generateCode(): string
    {
        do {
            $part1 = strtoupper(bin2hex(random_bytes(2)));
            $part2 = strtoupper(bin2hex(random_bytes(2)));
            $part3 = strtoupper(bin2hex(random_bytes(2)));
            $code = "CNC-{$part1}-{$part2}-{$part3}";
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Human readable label for type
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'trial_30m' => '৩০ মিনিট ট্রায়াল (30 Min Trial)',
            'trial_1h' => '১ ঘণ্টা ট্রায়াল (1 Hour Trial)',
            'custom_minutes' => "{$this->duration_minutes} মিনিট কাস্টম",
            'custom_days' => round($this->duration_minutes / 1440) . ' দিন কাস্টম',
            'lifetime' => 'লাইফটাইম অ্যাক্সেস (Lifetime)',
            default => ucfirst($this->type),
        };
    }

    /**
     * Check if currently active or expired
     */
    public function isExpired(): bool
    {
        if ($this->type === 'lifetime') {
            return false;
        }

        if (!$this->expires_at) {
            return false;
        }

        return Carbon::now()->greaterThan($this->expires_at);
    }
}
