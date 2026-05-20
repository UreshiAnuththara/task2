<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceAuthRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckDeviceAuthorized Middleware
 * ════════════════════════════════
 * Authentication is device-based ONLY.
 * The device token (cookie) is the sole factor — NOT user credentials.
 *
 * REQUIRED in bootstrap/app.php:
 *   $middleware->encryptCookies(except: ['dv_token']);
 *
 * All checks use device_token only — no user_id filter.
 * This means:
 *  - If ANY record for this token is approved  → ALL users on this device pass
 *  - If ANY record for this token is rejected  → ALL users on this device blocked
 *  - If ANY record for this token is pending   → ALL users on this device wait
 *
 * DeviceApprovalManager cascades every action to all records sharing the token:
 *  - Approve/Reapprove → pending+rejected → approved
 *  - Reject/Revoke     → pending+approved → rejected
 */
class CheckDeviceAuthorized
{
    public const COOKIE_NAME = 'dv_token';

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) return $next($request);

        // Admins always pass — no device check needed
        if ($user->isAdmin()) return $next($request);

        $token = $request->cookie(self::COOKIE_NAME);
        $ua    = $request->userAgent();

        // ── 1. APPROVED check — device_token only ────────────────────────
        // If this token is approved for ANY user → pass.
        // Approve one user = approve all users on this device.

        $approved = null;

        if ($token) {
            $approved = DeviceAuthRequest::where('device_token', $token)
                ->where('status', 'approved')
                ->first();
        }

        // NOTE: No user_agent fallback for approved check.
        // UA fallback could bypass a revoke (revoked token but same UA still approved).
        // Token is the only trusted identifier here.

        if ($approved) {
            // Sync cookie token → DB token if they drifted
            if ($token && $approved->device_token !== $token) {
                $conflict = DeviceAuthRequest::where('device_token', $token)
                    ->where('id', '!=', $approved->id)
                    ->exists();
                if (! $conflict) {
                    $approved->update(['device_token' => $token]);
                }
            }
            return $next($request);
        }

        // ── 2. REJECTED check — device_token only ────────────────────────
        // If this token is rejected for ANY user → block ALL users.
        // Reject/revoke one user = block all users on this device.
        // Blocked users get a fresh pending request so admin can re-review.

        $rejected = null;

        if ($token) {
            $rejected = DeviceAuthRequest::where('device_token', $token)
                ->where('status', 'rejected')
                ->first();
        }

        if (! $rejected && $ua) {
            $rejected = DeviceAuthRequest::where('user_id', $user->id)
                ->where('user_agent', $ua)
                ->where('status', 'rejected')
                ->first();
        }

        if ($rejected) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Rejected device → create a fresh pending request so admin
            // sees it in the pending tab (same as a brand new device).
            //
            // IMPORTANT: Always generate a brand-new token here.
            // The original cookie token already exists in DB (as rejected),
            // so reusing it would hit the unique constraint and silently fail.
            $alreadyPending = DeviceAuthRequest::where('user_id', $user->id)
                ->where('user_agent', $ua)
                ->where('status', 'pending')
                ->exists();

            if (! $alreadyPending) {
                // Generate a unique token not already in the table
                do {
                    $newToken = bin2hex(random_bytes(32));
                } while (DeviceAuthRequest::where('device_token', $newToken)->exists());

                DeviceAuthRequest::create([
                    'user_id'      => $user->id,
                    'device_token' => $newToken,
                    'ip_address'   => $request->ip(),
                    'user_agent'   => $ua,
                    'fingerprint'  => null,
                    'status'       => 'pending',
                    'requested_at' => now(),
                ]);
            }

            return redirect()->route('login')
                ->withErrors(['email' => 'pending_device_request']);
        }

        // ── 3. PENDING check — device_token only ─────────────────────────

        $pending = null;

        if ($token) {
            $pending = DeviceAuthRequest::where('device_token', $token)
                ->where('status', 'pending')
                ->first();
        }

        if (! $pending && $ua) {
            $pending = DeviceAuthRequest::where('user_id', $user->id)
                ->where('user_agent', $ua)
                ->where('status', 'pending')
                ->first();
        }

        if ($pending) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'pending_device_request']);
        }

        // ── 4. NO RECORD — create fresh pending request ───────────────────

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $newToken = $token ?: bin2hex(random_bytes(32));

        while (DeviceAuthRequest::where('device_token', $newToken)
                ->where('user_id', '!=', $user->id)
                ->exists()) {
            $newToken = bin2hex(random_bytes(32));
        }

        $alreadyPending = DeviceAuthRequest::where('user_id', $user->id)
            ->where('device_token', $newToken)
            ->where('status', 'pending')
            ->exists();

        if (! $alreadyPending) {
            DeviceAuthRequest::create([
                'user_id'      => $user->id,
                'device_token' => $newToken,
                'ip_address'   => $request->ip(),
                'user_agent'   => $ua,
                'fingerprint'  => null,
                'status'       => 'pending',
                'requested_at' => now(),
            ]);
        }

        return redirect()->route('login')
            ->withErrors(['email' => 'pending_device_request']);
    }
}