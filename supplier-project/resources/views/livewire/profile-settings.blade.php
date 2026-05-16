<div>
@push('styles')
<style>
    :root {
        --surface: #111827;
        --surface-2: #1a2234;
        --border: #1e2d45;
        --text: #f1f5f9;
        --text-muted: #64748b;
        --accent: #2563eb;
        --danger: #ef4444;
    }

    .profile-wrap {
        max-width: 720px;
        margin: 0 auto;
    }

    .page-header { margin-bottom: 28px; }
    .page-title { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.3px; }
    .page-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

    /* ── Avatar section ── */
    .avatar-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .avatar-img {
        width: 84px; height: 84px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--border);
    }

    .avatar-info .av-name { font-size: 20px; font-weight: 800; color: var(--text); letter-spacing: -0.3px; }
    .avatar-info .av-email { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
    .avatar-info .av-role {
        display: inline-flex; align-items: center; gap: 5px;
        margin-top: 8px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .av-role.admin { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .av-role.user  { background: rgba(96,165,250,0.12); color: #60a5fa; border: 1px solid rgba(96,165,250,0.25); }

    /* ── Tabs ── */
    .tabs {
        display: flex;
        gap: 4px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 24px;
        max-width: 320px;
    }

    .tab-btn {
        flex: 1;
        padding: 9px 16px;
        border-radius: 7px;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; cursor: pointer;
        transition: all 0.15s;
        color: var(--text-muted);
        background: none;
    }

    .tab-btn.active {
        background: var(--surface-2);
        color: var(--text);
        border: 1px solid var(--border);
    }

    /* ── Cards ── */
    .settings-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
    }

    .card-title { font-size: 15px; font-weight: 800; color: var(--text); }
    .card-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

    .card-body { padding: 22px 24px; }

    .form-group { margin-bottom: 18px; }

    .form-label {
        display: block;
        font-size: 12px; font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 0.8px;
        margin-bottom: 7px;
    }

    .form-input {
        width: 100%;
        padding: 12px 14px;
        background: var(--surface-2);
        border: 1.5px solid var(--border);
        border-radius: 9px;
        color: var(--text);
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    .form-input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .form-error { font-size: 12px; color: #f87171; margin-top: 5px; }
    .form-hint  { font-size: 12px; color: var(--text-muted); margin-top: 5px; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    .card-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px;
        background: var(--accent);
        color: #fff;
        border: none; border-radius: 9px;
        font-size: 14px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-primary:hover { background: #1d4ed8; }

    .flash-success {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 600; color: #86efac;
    }

    .flash-error-inline {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 600; color: #f87171;
    }

    /* ── Read-only role notice ── */
    .notice-box {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px;
        background: rgba(37,99,235,0.08);
        border: 1px solid rgba(37,99,235,0.2);
        border-radius: 10px;
        font-size: 13px; color: #93c5fd;
        margin-top: 4px;
    }
</style>
@endpush

<div class="profile-wrap">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-title">My Profile</div>
        <div class="page-subtitle">Manage your account details and password</div>
    </div>

    {{-- Avatar Card --}}
    <div class="avatar-card">
        <img src="{{ auth()->user()->profileImageUrl() }}"
             alt="{{ auth()->user()->name }}"
             class="avatar-img">
        <div class="avatar-info">
            <div class="av-name">{{ auth()->user()->name }}</div>
            <div class="av-email">{{ auth()->user()->email }}</div>
            <span class="av-role {{ auth()->user()->isAdmin() ? 'admin' : 'user' }}">
                {{ auth()->user()->isAdmin() ? '★ Administrator' : '● Regular User' }}
            </span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn {{ $activeTab === 'profile' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'profile')">
            Profile Info
        </button>
        <button class="tab-btn {{ $activeTab === 'password' ? 'active' : '' }}"
                wire:click="$set('activeTab', 'password')">
            Password
        </button>
    </div>


    {{-- ══ PROFILE TAB ══ --}}
    @if ($activeTab === 'profile')

    <div class="settings-card">
        <div class="card-header">
            <div class="card-title">Personal Information</div>
            <div class="card-subtitle">Update your name, email, and profile photo</div>
        </div>
        <div class="card-body">

            {{-- Name --}}
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input wire:model="name" class="form-input" type="text" placeholder="Your name">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input wire:model="email" class="form-input" type="email" placeholder="your@email.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Profile Photo --}}
            <div class="form-group">
                <label class="form-label">Profile Photo <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--text-muted);">(optional)</span></label>
                <input wire:model="photo" class="form-input" type="file" accept="image/*"
                    style="padding:8px 14px;color:var(--text-muted);">
                @error('photo') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">JPG or PNG, max 2MB. Changes immediately after saving.</div>
            </div>

            {{-- Role (read-only) --}}
            <div class="form-group">
                <label class="form-label">Role</label>
                <input class="form-input" type="text"
                    value="{{ auth()->user()->isAdmin() ? 'Administrator' : 'Regular User' }}"
                    disabled>
                <div class="notice-box">
                    ℹ️ Your role is managed by an Administrator and cannot be changed here.
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div>
                @if (session()->has('profile_success'))
                    <span class="flash-success">✓ {{ session('profile_success') }}</span>
                @endif
            </div>
            <button class="btn-primary" wire:click="updateProfile">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    @endif


    {{-- ══ PASSWORD TAB ══ --}}
    @if ($activeTab === 'password')

    <div class="settings-card">
        <div class="card-header">
            <div class="card-title">Change Password</div>
            <div class="card-subtitle">Use a strong password with at least 8 characters</div>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input wire:model="current_password" class="form-input" type="password"
                    placeholder="Enter current password">
                @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input wire:model="new_password" class="form-input" type="password"
                        placeholder="Minimum 8 characters">
                    @error('new_password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input wire:model="confirm_password" class="form-input" type="password"
                        placeholder="Repeat new password">
                    @error('confirm_password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div>
                @if (session()->has('password_success'))
                    <span class="flash-success">✓ {{ session('password_success') }}</span>
                @endif
            </div>
            <button class="btn-primary" wire:click="updatePassword">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Update Password
            </button>
        </div>
    </div>

    @endif

</div>
</div>