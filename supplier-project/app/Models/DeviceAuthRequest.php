<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceAuthRequest extends Model
{
    protected $fillable = [
        'user_id',       // nullable — stored for admin display only, not used in auth
        'device_token',
        'fingerprint',
        'ip_address',
        'user_agent',
        'status',
        'requested_at',
        'responded_at',
        'approved_by',
    ];

    protected $casts = [
        'fingerprint'  => 'array',
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    /**
     * The user this device belongs to.
     * Nullable — used for admin panel display only.
     * NOT used in authentication logic.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }

    /**
     * Human-friendly browser name extracted from user_agent.
     */
    public function browserLabel(): string
    {
        $ua = $this->user_agent ?? '';
        foreach ([
            'Edg'     => 'Microsoft Edge',
            'OPR'     => 'Opera',
            'Chrome'  => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari'  => 'Safari',
        ] as $key => $label) {
            if (str_contains($ua, $key)) return $label;
        }
        return 'Unknown Browser';
    }

    /**
     * Human-friendly OS name extracted from user_agent.
     */
    public function osLabel(): string
    {
        $ua = $this->user_agent ?? '';
        if (str_contains($ua, 'Windows'))    return 'Windows';
        if (str_contains($ua, 'Macintosh'))  return 'macOS';
        if (str_contains($ua, 'Linux'))      return 'Linux';
        if (str_contains($ua, 'Android'))    return 'Android';
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) return 'iOS';
        return 'Unknown OS';
    }
}