<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserRole extends Model
{
    protected $fillable = ['name', 'description', 'is_system', 'login_start', 'login_end'];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Whether this role has a login time restriction configured.
     */
    public function hasLoginRestriction(): bool
    {
        return !empty($this->login_start) && !empty($this->login_end);
    }

    /**
     * Human-readable label for the login window.
     * e.g. "8:00 AM – 6:00 PM"
     */
    public function loginWindowLabel(): string
    {
        if (! $this->hasLoginRestriction()) {
            return 'No Restriction';
        }

        $fmt = fn(string $hhmm) => Carbon::createFromFormat('H:i', $hhmm)->format('g:i A');

        $start = $fmt($this->login_start);
        $end   = $fmt($this->login_end);

        // Detect day vs overnight
        $crosses = $this->login_start >= $this->login_end;

        return $start . ' – ' . $end . ($crosses ? ' (overnight)' : '');
    }

    /**
     * Whether $nowMins falls within the role's login window.
     * Handles overnight ranges automatically.
     */
    public function isWithinLoginWindow(): bool
    {
        if (! $this->hasLoginRestriction()) {
            return true;
        }

        $now      = Carbon::now('Asia/Colombo');
        $nowMins  = $now->hour * 60 + $now->minute;

        [$sh, $sm] = array_map('intval', explode(':', $this->login_start));
        [$eh, $em] = array_map('intval', explode(':', $this->login_end));

        $startMins = $sh * 60 + $sm;
        $endMins   = $eh * 60 + $em;

        if ($startMins < $endMins) {
            // Same-day range: e.g. 08:00–18:00
            return $nowMins >= $startMins && $nowMins < $endMins;
        } else {
            // Overnight range: e.g. 18:00–08:00
            return $nowMins >= $startMins || $nowMins < $endMins;
        }
    }
}