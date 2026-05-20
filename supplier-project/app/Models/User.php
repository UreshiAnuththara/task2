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
        // Legacy shift columns kept for backward compat — no longer used for login restriction
        'shift',
        'shift_type',
        'shift_start',
        'shift_end',
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
     * Get the UserRole model for this user's role (if it exists).
     */
    public function userRole(): ?UserRole
    {
        if (!$this->role) return null;
        return UserRole::where('name', $this->role)->first();
    }

    /**
     * Check whether this user is allowed to log in right now.
     *
     * Admins         → always allowed.
     * No role found  → always allowed (fallback).
     * Role found     → check role's login_start / login_end window.
     */
    public function isWithinShift(): bool
    {
        if ($this->isAdmin()) return true;

        $roleModel = $this->userRole();

        if (!$roleModel) return true; // Unknown role → allow (safe fallback)

        return $roleModel->isWithinLoginWindow();
    }

    /**
     * Human-readable label shown in login error messages.
     */
    public function shiftLabel(): string
    {
        if ($this->isAdmin()) return 'Unrestricted (Admin)';

        $roleModel = $this->userRole();

        if (!$roleModel || !$roleModel->hasLoginRestriction()) {
            return 'No Restriction';
        }

        return $roleModel->loginWindowLabel();
    }

    /**
     * The shift type string stored in login_logs.shift column.
     * Kept for backward compat with LoginAnalytics.
     * Now derived from role login window direction.
     */
    public function effectiveShiftType(): ?string
    {
        if ($this->isAdmin()) return null;

        $roleModel = $this->userRole();

        if (!$roleModel || !$roleModel->hasLoginRestriction()) return null;

        // Day if start < end (same day), night if start >= end (overnight)
        return ($roleModel->login_start < $roleModel->login_end) ? 'day' : 'night';
    }
}