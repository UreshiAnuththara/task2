{{--
    resources/views/livewire/pages/auth/device-pending.blade.php
    ─────────────────────────────────────────────────────────────
    Shown after login when the user's device is not yet authorized.
    - Polls every 5 seconds via wire:poll
    - When admin approves → shows success banner + redirects to dashboard
    - When admin rejects → shows rejection message
--}}

<?php
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\DeviceAuthRequest;
use App\Http\Middleware\CheckDeviceAuthorized;

new #[Layout('components.layouts.auth')] class extends Component {

    public string $status      = 'pending'; // pending | approved | rejected
    public string $deviceToken = '';

    public function mount(): void
    {
        // Cookie name must match CheckDeviceAuthorized::COOKIE_NAME = 'dv_token'
        $this->deviceToken = request()->cookie(CheckDeviceAuthorized::COOKIE_NAME, '');

        if (! $this->deviceToken) {
            // No token → something went wrong, send back to login
            $this->redirect(route('login'));
            return;
        }

        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        if (! $this->deviceToken) return;

        $req = DeviceAuthRequest::where('device_token', $this->deviceToken)
                ->where('user_id', auth()->id())
                ->latest()
                ->first();

        if (! $req) return;

        $this->status = $req->status;

        if ($this->status === 'approved') {
            $this->dispatch('device-approved');
        }
    }

    // Called by wire:poll every 5 seconds
    public function poll(): void
    {
        $this->checkStatus();
    }
};
?>

{{-- Single root element wrapping everything including <style> --}}
<div
    wire:poll.5000ms="poll"
    style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;font-family:'Figtree',sans-serif;padding:24px;"
>

    {{-- ══════════════════════════════════════════════ APPROVED ══ --}}
    @if($status === 'approved')
        <div style="max-width:440px;width:100%;text-align:center;" x-data x-init="setTimeout(() => window.location.href = '{{ route('dashboard') }}', 3000)">

            {{-- Animated success circle --}}
            <div style="width:80px;height:80px;background:linear-gradient(135deg,#22c55e,#16a34a);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 8px 32px rgba(34,197,94,.35);animation:popIn .5s cubic-bezier(.34,1.56,.64,1) both;">
                <svg width="36" height="36" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>

            <h2 style="font-size:24px;font-weight:800;color:#0f172a;margin-bottom:8px;">Device Approved! 🎉</h2>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin-bottom:24px;">
                Your device has been approved by an administrator.<br>
                You will be redirected to the dashboard shortly.
            </p>

            {{-- Progress bar --}}
            <div style="height:4px;background:#e2e8f0;border-radius:99px;overflow:hidden;margin-bottom:20px;">
                <div style="height:100%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:99px;animation:progress 3s linear forwards;"></div>
            </div>

            <a href="{{ route('dashboard') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:#0f172a;color:#fff;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;">
                Go to Dashboard →
            </a>
        </div>

    {{-- ══════════════════════════════════════════════ REJECTED ══ --}}
    @elseif($status === 'rejected')
        <div style="max-width:440px;width:100%;text-align:center;">
            <div style="width:80px;height:80px;background:linear-gradient(135deg,#ef4444,#dc2626);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 8px 32px rgba(239,68,68,.3);">
                <svg width="36" height="36" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>

            <h2 style="font-size:24px;font-weight:800;color:#0f172a;margin-bottom:8px;">Request Rejected</h2>
            <p style="font-size:14px;color:#64748b;line-height:1.6;margin-bottom:24px;">
                Your device authorization request was rejected by an administrator.<br>
                Please contact your administrator for assistance.
            </p>

            <a href="{{ route('login') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:#0f172a;color:#fff;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;">
                Back to Login
            </a>
        </div>

    {{-- ══════════════════════════════════════════════ PENDING ══ --}}
    @else
        <div style="max-width:480px;width:100%;">

            {{-- Card --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 4px 32px rgba(0,0,0,.06);padding:40px;text-align:center;">

                {{-- Animated lock icon --}}
                <div style="width:72px;height:72px;background:#eff6ff;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;position:relative;">
                    <svg width="32" height="32" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round"/>
                    </svg>
                    {{-- Pulse ring --}}
                    <div style="position:absolute;inset:-6px;border-radius:24px;border:2px solid #bfdbfe;animation:pulse 2s infinite;"></div>
                </div>

                <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin-bottom:8px;">Waiting for Approval</h2>
                <p style="font-size:14px;color:#64748b;line-height:1.7;margin-bottom:32px;">
                    This device has not been recognized.<br>
                    An administrator needs to approve your device before you can access the system.
                </p>

                {{-- Status pill --}}
                <div style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#fef9c3;border:1px solid #fde68a;border-radius:99px;margin-bottom:32px;">
                    <span style="width:8px;height:8px;background:#eab308;border-radius:50%;display:inline-block;animation:blink 1.4s infinite;"></span>
                    <span style="font-size:13px;font-weight:700;color:#854d0e;">Pending Admin Approval</span>
                </div>

                {{-- Info steps --}}
                <div style="background:#f8fafc;border-radius:12px;padding:20px;text-align:left;margin-bottom:28px;">
                    <div style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;">What happens next?</div>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        @foreach([
                            ['🔔', 'Admin receives a notification about your new device.'],
                            ['✅', 'Once approved, this page will update automatically.'],
                            ['🚀', 'You\'ll be redirected to the dashboard instantly.'],
                        ] as [$icon, $text])
                            <div style="display:flex;align-items:flex-start;gap:10px;">
                                <span style="font-size:15px;flex-shrink:0;margin-top:1px;">{{ $icon }}</span>
                                <span style="font-size:13px;color:#64748b;line-height:1.5;">{{ $text }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Polling indicator --}}
                <div style="display:flex;align-items:center;justify-content:center;gap:8px;color:#94a3b8;font-size:12px;">
                    <div style="display:flex;gap:3px;">
                        <div style="width:5px;height:5px;background:#94a3b8;border-radius:50%;animation:bounce 1.2s infinite 0s;"></div>
                        <div style="width:5px;height:5px;background:#94a3b8;border-radius:50%;animation:bounce 1.2s infinite 0.2s;"></div>
                        <div style="width:5px;height:5px;background:#94a3b8;border-radius:50%;animation:bounce 1.2s infinite 0.4s;"></div>
                    </div>
                    <span>Checking for approval every 5 seconds…</span>
                </div>

            </div>

            {{-- Logout link --}}
            <div style="text-align:center;margin-top:20px;">
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;font-size:13px;color:#94a3b8;cursor:pointer;font-family:'Figtree',sans-serif;text-decoration:underline;text-underline-offset:3px;">
                        Sign out and try a different account
                    </button>
                </form>
            </div>

        </div>
    @endif

    {{-- Styles inside root div — fixes MultipleRootElementsDetectedException --}}
    <style>
    @keyframes popIn {
        from { transform: scale(0); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
    @keyframes progress {
        from { width: 0%; }
        to   { width: 100%; }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .4; transform: scale(1.08); }
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: .3; }
    }
    @keyframes bounce {
        0%, 80%, 100% { transform: translateY(0); }
        40%            { transform: translateY(-6px); }
    }
    </style>

</div>