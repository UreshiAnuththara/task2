<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use App\Models\LoginLog;
use App\Models\DeviceAuthRequest;
use Carbon\Carbon;

new #[Layout('components.layouts.auth')] class extends Component {

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool   $remember    = false;
    public string $deviceToken = '';
    public string $fingerprint = '';

    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        $user = Auth::user();

        // ── Shift restriction ──────────────────────────────────────────────
        if (! $user->isWithinShift()) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            $now = Carbon::now('Asia/Colombo');
            throw ValidationException::withMessages([
                'email' => "Access denied. You are assigned to the {$user->shiftLabel()}. "
                         . "Current time is {$now->format('h:i A')} (Sri Lanka). "
                         . "Please log in during your assigned shift hours.",
            ]);
        }

        // ── Device authorization (non-admins only) ─────────────────────────
        if (! $user->isAdmin()) {
            $browserToken = trim($this->deviceToken); // token from JS / cookie
            $ua           = request()->userAgent();
            $ip           = request()->ip();
            $fpData       = null;

            try { $fpData = $this->fingerprint ? json_decode($this->fingerprint, true) : null; } catch (\Exception $e) {}

            // ── STEP 1: APPROVED check — device_token only (no user_id filter) ──
            // If this token is approved for ANY user → this user passes too.
            // Approve one user on a device = approve all users on that device.
            $approvedRecord = null;

            if ($browserToken) {
                $approvedRecord = DeviceAuthRequest::where('device_token', $browserToken)
                    ->where('status', 'approved')
                    ->first();
            }

            // NOTE: No user_agent fallback for approved check.
            // UA fallback could bypass a revoke (same UA but token revoked).

            if ($approvedRecord) {
                // Sync cookie token → DB token if they drifted
                if ($browserToken && $approvedRecord->device_token !== $browserToken) {
                    $conflict = DeviceAuthRequest::where('device_token', $browserToken)
                        ->where('id', '!=', $approvedRecord->id)
                        ->exists();
                    if (! $conflict) {
                        $approvedRecord->update(['device_token' => $browserToken]);
                    }
                }

                $this->_completeLogin($user, $approvedRecord->device_token);
                return;
            }

            // ── STEP 2: REJECTED check — device_token only (no user_id filter) ──
            // If this token is rejected for ANY user → block ALL users on this device.
            // Reject/revoke one user = block all users on that device.
            $rejectedRecord = null;

            if ($browserToken) {
                $rejectedRecord = DeviceAuthRequest::where('device_token', $browserToken)
                    ->where('status', 'rejected')
                    ->first();
            }
            if (! $rejectedRecord) {
                $rejectedRecord = DeviceAuthRequest::where('user_id', $user->id)
                    ->where('user_agent', $ua)
                    ->where('status', 'rejected')
                    ->first();
            }

            if ($rejectedRecord) {
                // Rejected device → create a fresh pending request so admin
                // sees it in the pending tab. Always use a new token —
                // the original token already exists in DB (rejected) and
                // reusing it would hit the unique constraint and silently fail.
                $alreadyPending = DeviceAuthRequest::where('user_id', $user->id)
                    ->where('user_agent', $ua)
                    ->where('status', 'pending')
                    ->exists();

                if (! $alreadyPending) {
                    do {
                        $freshToken = bin2hex(random_bytes(32));
                    } while (DeviceAuthRequest::where('device_token', $freshToken)->exists());

                    DeviceAuthRequest::create([
                        'user_id'      => $user->id,
                        'device_token' => $freshToken,
                        'ip_address'   => request()->ip(),
                        'user_agent'   => $ua,
                        'fingerprint'  => null,
                        'status'       => 'pending',
                        'requested_at' => now(),
                    ]);
                }

                Auth::logout();
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'email' => 'pending_device_request',
                ]);
            }

            // ── STEP 3: PENDING check ──────────────────────────────────────
            $pendingRecord = null;
            if ($browserToken) {
                $pendingRecord = DeviceAuthRequest::where('device_token', $browserToken)
                    ->where('status', 'pending')
                    ->first();
            }
            if (! $pendingRecord) {
                $pendingRecord = DeviceAuthRequest::where('user_id', $user->id)
                    ->where('user_agent', $ua)
                    ->where('status', 'pending')
                    ->first();
            }

            if (! $pendingRecord) {
                // Completely new device — create pending record
                // Use browser token if available, otherwise generate one
                $newToken = $browserToken ?: bin2hex(random_bytes(32));

                // Avoid unique constraint if another user owns this token
                if (DeviceAuthRequest::where('device_token', $newToken)
                    ->where('user_id', '!=', $user->id)->exists()) {
                    $newToken = bin2hex(random_bytes(32));
                }

                $pendingRecord = DeviceAuthRequest::create([
                    'user_id'      => $user->id,
                    'device_token' => $newToken,
                    'fingerprint'  => $fpData,
                    'ip_address'   => $ip,
                    'user_agent'   => $ua,
                    'status'       => 'pending',
                    'requested_at' => now(),
                ]);
            } else {
                // Update fingerprint/IP on existing pending record
                $pendingRecord->update([
                    'fingerprint' => $fpData ?? $pendingRecord->fingerprint,
                    'ip_address'  => $ip,
                ]);
                // Sync browser token → DB so cookie + DB stay in sync
                if ($browserToken && $pendingRecord->device_token !== $browserToken) {
                    $conflict = DeviceAuthRequest::where('device_token', $browserToken)
                        ->where('id', '!=', $pendingRecord->id)->exists();
                    if (! $conflict) {
                        $pendingRecord->update(['device_token' => $browserToken]);
                    }
                }
            }

            // Block — dispatch event so JS writes the pending token to cookie
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            $this->dispatch('set-device-cookie', token: $pendingRecord->device_token);

            throw ValidationException::withMessages(['email' => 'pending_device_request']);
        }

        // ── Admin — no device check ────────────────────────────────────────
        $this->_completeLogin($user, null);
    }

    /**
     * Finalize a successful login: log it, regenerate session, dispatch JS event.
     * For approved non-admin users: dispatches 'device-approved-redirect' so JS
     * writes the cookie and navigates (avoids Livewire lost-cookie bug).
     * For admins: uses standard Livewire redirect.
     */
    private function _completeLogin($user, ?string $approvedToken): void
    {
        try {
            LoginLog::create([
                'user_id'      => $user->id,
                'shift'        => $user->effectiveShiftType(),
                'role'         => $user->role,
                'logged_in_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('LoginLog create failed: ' . $e->getMessage());
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        if ($approvedToken !== null) {
            // Non-admin: let JS write cookie then navigate
            $this->dispatch('device-approved-redirect', [
                'token'       => $approvedToken,
                'redirectUrl' => route('suppliers.index'),
            ]);
        } else {
            // Admin
            $this->redirect(route('suppliers.index'), navigate: true);
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) return;
        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
};
?>

<div style="position:fixed;inset:0;display:flex;font-family:'Figtree',sans-serif;overflow:hidden;">

    {{-- ══ LEFT ══ --}}
    <div style="width:920px;flex-shrink:0;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:100px 96px;overflow-y:auto;">

        {{-- Brand --}}
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:44px;">
            <div style="width:52px;height:52px;background:linear-gradient(135deg,#1e3a8a,#2563eb);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                <svg width="28" height="28" viewBox="0 0 40 40" fill="none">
                    <rect x="4" y="20" width="8" height="16" rx="1" fill="white" opacity="0.9"/>
                    <rect x="16" y="10" width="8" height="26" rx="1" fill="white"/>
                    <rect x="28" y="4" width="8" height="32" rx="1" fill="white" opacity="0.75"/>
                    <path d="M2 38 L38 38" stroke="white" stroke-width="2" stroke-linecap="round" opacity="0.5"/>
                </svg>
            </div>
            <div>
                <div style="font-size:20px;font-weight:800;color:#0f172a;letter-spacing:-0.3px;">ABC Company</div>
                <div style="font-size:11px;color:#94a3b8;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-top:1px;">Management Portal</div>
            </div>
        </div>

        <div style="margin-bottom:28px;">
            <h1 style="font-size:26px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;margin-bottom:6px;">Welcome back</h1>
            <p style="font-size:14px;color:#64748b;line-height:1.5;">Sign in to your account to continue</p>
        </div>

        {{-- ── Messages ── --}}
        @if($errors->any())
            @php
                $rawMsg     = $errors->first();
                $isPending  = $rawMsg === 'pending_device_request';
                $isRejected = $rawMsg === 'rejected_device';
            @endphp

            @if($isPending)
                {{-- ── Pending device message (yellow) ── --}}
                <div style="padding:16px 18px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;margin-bottom:18px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div style="width:36px;height:36px;background:#fef3c7;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:800;color:#92400e;">Device Authorization Pending</div>
                            <div style="font-size:12px;color:#b45309;margin-top:1px;">Waiting for administrator approval</div>
                        </div>
                    </div>
                    <div style="font-size:12px;color:#78350f;line-height:1.6;background:#fef9c3;border-radius:8px;padding:10px 12px;">
                        ℹ️ Your login request has been sent to the administrator.<br>
                        Once approved, you can sign in from this device normally.<br>
                        <strong>Please try again after receiving approval.</strong>
                    </div>
                </div>

            @elseif($isRejected)
                {{-- ── Rejected device message (red) ── --}}
                <div style="padding:16px 18px;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;margin-bottom:18px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div style="width:36px;height:36px;background:#fee2e2;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="18" height="18" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M15 9l-6 6M9 9l6 6" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:800;color:#991b1b;">Device Access Rejected</div>
                            <div style="font-size:12px;color:#dc2626;margin-top:1px;">This device has been blocked by the administrator</div>
                        </div>
                    </div>
                    <div style="font-size:12px;color:#7f1d1d;line-height:1.6;background:#fee2e2;border-radius:8px;padding:10px 12px;">
                        🚫 Your access from this device has been rejected by the administrator.<br>
                        Please contact your administrator to request access from this device.
                    </div>
                </div>

            @else
                {{-- ── Generic error message ── --}}
                <div style="padding:13px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#dc2626;font-size:13px;font-weight:600;margin-bottom:18px;display:flex;gap:10px;align-items:flex-start;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:1px;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span style="line-height:1.5;">{{ $rawMsg }}</span>
                </div>
            @endif
        @endif

        {{-- ── Form ── --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div>
                <label style="display:block;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:7px;">Email Address</label>
                <input wire:model="email" type="email" placeholder="you@abccompany.com" autofocus
                    autocomplete="off"
                    style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;box-sizing:border-box;font-family:'Figtree',sans-serif;"
                    onfocus="this.style.borderColor='#2563eb';this.style.background='#fff'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
            </div>

            <div>
                <label style="display:block;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:7px;">Password</label>
                <input wire:model="password" type="password" placeholder="••••••••" wire:keydown.enter="login"
                    autocomplete="new-password"
                    style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;box-sizing:border-box;font-family:'Figtree',sans-serif;"
                    onfocus="this.style.borderColor='#2563eb';this.style.background='#fff'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
                @error('password') <div style="font-size:12px;color:#dc2626;margin-top:5px;">{{ $message }}</div> @enderror
            </div>

            <label style="display:flex;align-items:center;gap:9px;cursor:pointer;user-select:none;">
                <input wire:model="remember" type="checkbox"
                    style="width:16px;height:16px;border:1.5px solid #cbd5e1;border-radius:4px;cursor:pointer;accent-color:#2563eb;flex-shrink:0;">
                <span style="font-size:13px;font-weight:600;color:#374151;">Remember me</span>
            </label>

            {{-- Hidden device fields --}}
            <input wire:model="deviceToken" type="hidden" id="device-token-field">
            <input wire:model="fingerprint"  type="hidden" id="fingerprint-field">

            <button wire:click="login" wire:loading.attr="disabled"
                style="width:100%;padding:12px;background:#2563eb;color:#fff;font-size:14px;font-weight:700;border:none;border-radius:8px;cursor:pointer;font-family:'Figtree',sans-serif;margin-top:4px;"
                onmouseover="this.style.background='#1d4ed8'"
                onmouseout="this.style.background='#2563eb'">
                <span wire:loading.remove wire:target="login">Sign In →</span>
                <span wire:loading wire:target="login" style="opacity:0.8;">Signing in...</span>
            </button>

        </div>

        <div style="margin-top:36px;padding-top:20px;border-top:1px solid #f1f5f9;text-align:center;">
            <span style="font-size:12px;color:#cbd5e1;display:inline-flex;align-items:center;gap:6px;">
                <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                System online · Contact admin for access
            </span>
        </div>

    </div>

    {{-- ══ RIGHT — Branding ══ --}}
    <div style="flex:1;position:relative;overflow:hidden;">
        @php $bgImage = asset('storage/Images/Inbizsys.jpg'); @endphp
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#1e40af 100%);"></div>
        @if(file_exists(public_path('storage/Images/Inbizsys.jpg')))
            <img src="{{ $bgImage }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
        @endif
        <div style="position:absolute;bottom:28px;right:28px;z-index:2;">
            <span style="font-size:11px;color:rgba(255,255,255,0.35);font-weight:500;">Powered by Inbizsys © {{ date('Y') }}</span>
        </div>
    </div>

</div>

{{-- ══ Device JS ══ --}}
<script>
(function () {
    const COOKIE_NAME = 'dv_token';
    const COOKIE_DAYS = 365;

    function getCookie(name) {
        for (const c of document.cookie.split(';').map(s => s.trim())) {
            if (c.startsWith(name + '=')) return decodeURIComponent(c.slice(name.length + 1));
        }
        return null;
    }

    // Plain JS cookie — excluded from Laravel encryption via:
    // $middleware->encryptCookies(except: ['dv_token']);
    function setCookie(name, value, days) {
        const exp = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)};expires=${exp};path=/;SameSite=Lax`;
    }

    function buildFingerprint() {
        const c   = document.createElement('canvas');
        const ctx = c.getContext('2d');
        ctx.textBaseline = 'top';
        ctx.font = '14px Arial';
        ctx.fillText('device-fp', 2, 2);
        return {
            screen:   `${screen.width}x${screen.height}@${screen.colorDepth}bit`,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            lang:     navigator.language,
            platform: navigator.platform,
            cores:    navigator.hardwareConcurrency,
            canvas:   c.toDataURL().slice(-50),
        };
    }

    async function hashStr(str) {
        const buf = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(str));
        return Array.from(new Uint8Array(buf)).map(b => b.toString(16).padStart(2,'0')).join('');
    }

    // ── Clear email & password on every page load/refresh ──
    // Prevents browser saved-password autofill from pre-filling the fields.
    function clearAuthFields() {
        const emailEl = document.querySelector('input[type="email"]');
        const passEl  = document.querySelector('input[type="password"]');
        if (emailEl) { emailEl.value = ''; emailEl.dispatchEvent(new Event('input')); }
        if (passEl)  { passEl.value  = ''; passEl.dispatchEvent(new Event('input')); }
    }

    // Run immediately and after a short delay (some browsers autofill after DOM ready)
    clearAuthFields();
    setTimeout(clearAuthFields, 100);
    setTimeout(clearAuthFields, 300);

    document.addEventListener('livewire:navigated',   clearAuthFields);
    document.addEventListener('livewire:initialized',  clearAuthFields);

    async function init() {
        // Always re-use existing cookie token if present
        let token = getCookie(COOKIE_NAME);
        const fp  = buildFingerprint();

        if (!token) {
            // Generate stable token from fingerprint — same browser = same token
            token = await hashStr(JSON.stringify(fp) + navigator.userAgent);
        }

        // Refresh cookie lifetime on every page load
        setCookie(COOKIE_NAME, token, COOKIE_DAYS);

        function pushToLivewire() {
            const tf = document.getElementById('device-token-field');
            const ff = document.getElementById('fingerprint-field');
            if (tf) { tf.value = token;              tf.dispatchEvent(new Event('input')); }
            if (ff) { ff.value = JSON.stringify(fp); ff.dispatchEvent(new Event('input')); }
        }

        pushToLivewire();
        document.addEventListener('livewire:navigated',   pushToLivewire);
        document.addEventListener('livewire:initialized', pushToLivewire);
    }

    init().catch(console.error);

    document.addEventListener('livewire:init', function () {

        // ── Pending: server gave us a token — save it to cookie ──
        // This ensures next login attempt sends the same token the DB has.
        Livewire.on('set-device-cookie', (payload) => {
            const p = Array.isArray(payload) ? payload[0] : payload;
            if (p?.token) {
                setCookie(COOKIE_NAME, p.token, COOKIE_DAYS);
                // Also update hidden field immediately
                const tf = document.getElementById('device-token-field');
                if (tf) { tf.value = p.token; tf.dispatchEvent(new Event('input')); }
            }
        });

        // ── Approved: write cookie then navigate (Livewire redirect loses cookies) ──
        Livewire.on('device-approved-redirect', (payload) => {
            const p = Array.isArray(payload) ? payload[0] : payload;
            if (p?.token) setCookie(COOKIE_NAME, p.token, COOKIE_DAYS);
            setTimeout(() => {
                window.location.href = p?.redirectUrl || '/suppliers';
            }, 100);
        });

    });
})();
</script>