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
        'shift',        // legacy column (kept for backward compat)
        'shift_type',   // 'day' | 'night' | null
        'shift_start',  // HH:MM  e.g. "08:00"
        'shift_end',    // HH:MM  e.g. "18:00"
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
     *
     * Admins   → always allowed.
     * No shift → always allowed.
     * shift_type = 'day'   → allowed between shift_start and shift_end (same day, start < end).
     * shift_type = 'night' → allowed between shift_start and shift_end (crosses midnight, start > end).
     */
    public function isWithinShift(): bool
    {
        if ($this->isAdmin()) return true;

        // Support both new (shift_type) and legacy (shift) columns
        $type = $this->shift_type ?? $this->shift;

        if (!$type || $type === 'none') return true;

        $now     = Carbon::now('Asia/Colombo');
        $nowMins = $now->hour * 60 + $now->minute;

        $startMins = $this->timeToMinutes($this->shift_start ?? '08:00');
        $endMins   = $this->timeToMinutes($this->shift_end   ?? '18:00');

        return $this->isInTimeRange($nowMins, $startMins, $endMins);
    }

    /**
     * True if $nowMins is within [$startMins, $endMins).
     * Handles overnight ranges (e.g. 18:00 → 08:00).
     */
    private function isInTimeRange(int $nowMins, int $startMins, int $endMins): bool
    {
        if ($startMins < $endMins) {
            // Same-day range: e.g. 08:00–18:00
            return $nowMins >= $startMins && $nowMins < $endMins;
        } else {
            // Overnight range: e.g. 18:00–08:00
            return $nowMins >= $startMins || $nowMins < $endMins;
        }
    }

    private function timeToMinutes(string $time): int
    {
        [$h, $m] = array_map('intval', explode(':', $time));
        return $h * 60 + $m;
    }

    /**
     * Human-readable shift label shown in login error messages.
     */
    public function shiftLabel(): string
    {
        $type  = $this->shift_type ?? $this->shift;
        $start = $this->shift_start ?? '08:00';
        $end   = $this->shift_end   ?? '18:00';

        $fmt = fn(string $hhmm) => Carbon::createFromFormat('H:i', $hhmm)->format('g:i A');

        return match ($type) {
            'day'   => 'Day Shift ('   . $fmt($start) . ' – ' . $fmt($end) . ')',
            'night' => 'Night Shift (' . $fmt($start) . ' – ' . $fmt($end) . ')',
            default => 'No Restriction',
        };
    }

    /**
     * The shift type string stored in login_logs.shift.
     * Admins and no-shift users → null.
     * Others → 'day' or 'night'.
     */
    public function effectiveShiftType(): ?string
    {
        if ($this->isAdmin()) return null;

        $type = $this->shift_type ?? $this->shift;

        if (!$type || $type === 'none') return null;

        return $type; // 'day' or 'night'
    }
}