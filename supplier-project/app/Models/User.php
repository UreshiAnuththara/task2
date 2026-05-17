<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'shift',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function profileImageUrl(): string
    {
        if ($this->profile_image && file_exists(storage_path('app/public/' . $this->profile_image))) {
            return asset('storage/' . $this->profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=2563eb&color=fff&size=128&bold=true';
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }

    /**
     * Check whether this user is currently allowed to log in based on shift.
     * Admins (role=admin) always have access regardless of shift.
     * Users with no shift assigned can always log in.
     */
    public function isWithinShift(): bool
    {
        // Admins bypass shift restriction
        if ($this->isAdmin()) {
            return true;
        }

        // No shift assigned — unrestricted
        if (! $this->shift) {
            return true;
        }

        $now  = Carbon::now();
        $hour = (int) $now->format('H'); // 0-23

        if ($this->shift === 'day') {
            // Day shift: 08:00 – 18:00  (hour >= 8 AND hour < 18)
            return $hour >= 8 && $hour < 18;
        }

        if ($this->shift === 'night') {
            // Night shift: 18:00 – 08:00  (hour >= 18 OR hour < 8)
            return $hour >= 18 || $hour < 8;
        }

        return true;
    }

    /**
     * Human-readable shift label.
     */
    public function shiftLabel(): string
    {
        return match($this->shift) {
            'day'   => 'Day (8 AM – 6 PM)',
            'night' => 'Night (6 PM – 8 AM)',
            default => 'No Restriction',
        };
    }
}