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

new #[Layout('components.layouts.auth')] class extends Component {

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // ── Shift restriction ──────────────────────────────────────────────
        if (! $user->isWithinShift()) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            $label = $user->shift === 'day'
                ? 'Day Shift (8:00 AM – 6:00 PM)'
                : 'Night Shift (6:00 PM – 8:00 AM)';

            throw ValidationException::withMessages([
                'email' => "Access denied. You are assigned to the {$label}. Please log in during your shift hours.",
            ]);
        }

        // ── Log the successful login ───────────────────────────────────────
        try {
            LoginLog::create([
                'user_id'      => $user->id,
                'shift'        => $user->shift,
                'role'         => $user->role,
                'logged_in_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't block login if logging fails
            \Log::error('LoginLog create failed: ' . $e->getMessage());
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();
        $this->redirect(route('suppliers.index'), navigate: true);
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

    {{-- ══ LEFT — Login Form ══ --}}
    <div style="width:460px;flex-shrink:0;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:48px 44px;overflow-y:auto;">

        {{-- ABC Company --}}
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

        @if ($errors->any())
        <div style="padding:11px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#dc2626;font-size:13px;font-weight:600;margin-bottom:18px;display:flex;gap:8px;align-items:flex-start;">
            <svg width="15" height="15" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:1px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <div style="display:flex;flex-direction:column;gap:16px;">

            <div>
                <label style="display:block;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:7px;">Email Address</label>
                <input wire:model="email" type="email" placeholder="you@abccompany.com" autofocus
                    style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;box-sizing:border-box;font-family:'Figtree',sans-serif;"
                    onfocus="this.style.borderColor='#2563eb';this.style.background='#fff'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
                @error('email') <div style="font-size:12px;color:#dc2626;margin-top:5px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label style="display:block;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:7px;">Password</label>
                <input wire:model="password" type="password" placeholder="••••••••" wire:keydown.enter="login"
                    style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;background:#f8fafc;outline:none;box-sizing:border-box;font-family:'Figtree',sans-serif;"
                    onfocus="this.style.borderColor='#2563eb';this.style.background='#fff'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
                @error('password') <div style="font-size:12px;color:#dc2626;margin-top:5px;">{{ $message }}</div> @enderror
            </div>

            {{-- Remember Me --}}
            <label style="display:flex;align-items:center;gap:9px;cursor:pointer;user-select:none;">
                <input wire:model="remember" type="checkbox"
                    style="width:16px;height:16px;border:1.5px solid #cbd5e1;border-radius:4px;cursor:pointer;accent-color:#2563eb;flex-shrink:0;">
                <span style="font-size:13px;font-weight:600;color:#374151;">Remember me</span>
            </label>

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

    {{-- ══ RIGHT — Inbizsys Branding ══ --}}
    <div style="flex:1;position:relative;overflow:hidden;">

        {{-- Background image - using asset() helper for correct path --}}
        @php $bgImage = asset('storage/Images/Inbizsys.jpg'); @endphp

        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#1e40af 100%);"></div>

        @if(file_exists(public_path('storage/Images/Inbizsys.jpg')))
        <img
            src="{{ $bgImage }}"
            alt=""
            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"
        >
        @endif

        <div style="position:absolute;inset:0;background:rgba(10,20,50,0.45);"></div>

        <div style="position:relative;z-index:2;height:100%;display:flex;flex-direction:column;align-items:right;justify-content:right;">

            {{-- <div style="width:80px;height:80px;background:rgba(255,255,255,0.12);border:2px solid rgba(255,255,255,0.3);border-radius:22px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;backdrop-filter:blur(8px);">
                <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                    <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                </svg>
            </div>

            <div style="font-size:42px;font-weight:800;color:#fff;letter-spacing:-1.5px;">Inbizsys</div> --}}

            {{-- Shift info box --}}
            <div style="margin-top:32px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:12px;padding:18px 28px;backdrop-filter:blur(8px);text-align:center;max-width:300px;">
                <div style="font-size:11px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;font-weight:600;">Shift Schedule</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,0.85);">
                        <span style="width:8px;height:8px;border-radius:50%;background:#fbbf24;flex-shrink:0;"></span>
                        <span><strong>Day Shift</strong> — 8:00 AM to 6:00 PM</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,0.85);">
                        <span style="width:8px;height:8px;border-radius:50%;background:#818cf8;flex-shrink:0;"></span>
                        <span><strong>Night Shift</strong> — 6:00 PM to 8:00 AM</span>
                    </div>
                    
                </div>
            </div>

        </div>

        <div style="position:absolute;bottom:28px;right:28px;z-index:2;">
            <span style="font-size:11px;color:rgba(255,255,255,0.35);font-weight:500;">Powered by Inbizsys © {{ date('Y') }}</span>
        </div>

    </div>

</div>