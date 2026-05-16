<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
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

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function profileImageUrl(): string
    {
        if ($this->profile_image && file_exists(storage_path('app/public/' . $this->profile_image))) {
            return asset('storage/' . $this->profile_image);
        }

        // Generate avatar from initials
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=1d4ed8&color=fff&size=128&bold=true';
    }
}