<div>
@push('styles')
<style>
    .prof-wrap { max-width: 680px; margin: 0 auto; }

    .page-hd { margin-bottom: 24px; }
    .page-hd h1 { font-size: 20px; font-weight: 800; color: #0f172a; }
    .page-hd p  { font-size: 13px; color: #64748b; margin-top: 2px; }

    .av-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
        padding: 22px 24px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 18px; flex-wrap: wrap;
    }

    .av-img { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; }
    .av-name  { font-size: 18px; font-weight: 800; color: #0f172a; }
    .av-email { font-size: 13px; color: #64748b; margin-top: 2px; }
    .av-role  { display: inline-flex; align-items: center; margin-top: 6px; padding: 3px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .av-role.admin  { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .av-role.custom { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

    .tabs { display: flex; gap: 3px; background: #f1f5f9; border-radius: 9px; padding: 3px; margin-bottom: 20px; max-width: 280px; }
    .tab-btn { flex: 1; padding: 8px 14px; border-radius: 7px; font-size: 13px; font-weight: 700; font-family: 'Figtree', sans-serif; border: none; cursor: pointer; color: #64748b; background: none; transition: all 0.12s; }
    .tab-btn.active { background: #fff; color: #0f172a; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

    .settings-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .card-hd { padding: 18px 22px 14px; border-bottom: 1px solid #f1f5f9; }
    .card-hd h2 { font-size: 15px; font-weight: 800; color: #0f172a; }
    .card-hd p  { font-size: 12px; color: #64748b; margin-top: 2px; }
    .card-body { padding: 20px 22px; }
    .card-ft { padding: 14px 22px 18px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px; }
    .form-input {
        width: 100%; padding: 10px 12px;
        background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 8px;
        font-size: 13px; color: #0f172a; font-family: 'Figtree', sans-serif; outline: none;
        transition: border-color 0.15s, background 0.15s;
    }
    .form-input:focus { border-color: #2563eb; background: #fff; }
    .form-input:disabled { opacity: 0.55; cursor: not-allowed; }
    .form-error { font-size: 11px; color: #dc2626; margin-top: 4px; }
    .form-hint  { font-size: 11px; color: #94a3b8; margin-top: 4px; }
    .form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 560px) { .form-row { grid-template-columns: 1fr; } }

    .btn-primary { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; font-family: 'Figtree', sans-serif; cursor: pointer; }
    .btn-primary:hover { background: #1d4ed8; }

    .flash-ok { font-size: 13px; font-weight: 600; color: #15803d; display: inline-flex; align-items: center; gap: 6px; }

    .notice-info { display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 12px; color: #2563eb; margin-top: 4px; }
</style>
@endpush

<div class="prof-wrap">

    <div class="page-hd">
        <h1>My Profile</h1>
        <p>Update your personal information and password</p>
    </div>

    {{-- Avatar Card --}}
    <div class="av-card">
        <img src="{{ auth()->user()->profileImageUrl() }}" alt="" class="av-img">
        <div>
            <div class="av-name">{{ auth()->user()->name }}</div>
            <div class="av-email">{{ auth()->user()->email }}</div>
            <span class="av-role {{ auth()->user()->isAdmin() ? 'admin' : 'custom' }}">
                {{ auth()->user()->role ?? 'User' }}
            </span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn {{ $activeTab === 'profile' ? 'active' : '' }}" wire:click="$set('activeTab','profile')">Profile</button>
        <button class="tab-btn {{ $activeTab === 'password' ? 'active' : '' }}" wire:click="$set('activeTab','password')">Password</button>
    </div>

    {{-- Profile Tab --}}
    @if($activeTab === 'profile')
    <div class="settings-card">
        <div class="card-hd">
            <h2>Personal Information</h2>
            <p>Update your name, email, and profile photo</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input wire:model="name" class="form-input" type="text" placeholder="Your name">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input wire:model="email" class="form-input" type="email" placeholder="you@company.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group" x-data="{ uploading: false, progress: 0 }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false; progress = 0"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <label class="form-label">Profile Photo <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;">(optional)</span></label>
                <input wire:model="photo" type="file" accept="image/*" class="form-input" style="padding:7px 12px;color:#64748b;">
                <div x-show="uploading" style="margin-top:6px;background:#e2e8f0;border-radius:4px;height:4px;overflow:hidden;">
                    <div :style="'width:' + progress + '%'" style="height:100%;background:#2563eb;transition:width 0.2s;"></div>
                </div>
                <div x-show="uploading" style="font-size:11px;color:#2563eb;margin-top:4px;">Uploading... <span x-text="progress"></span>%</div>
                @error('photo') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <input class="form-input" type="text" value="{{ auth()->user()->role ?? 'User' }}" disabled>
                <div class="notice-info">ℹ️ Your role is assigned by the administrator and cannot be changed here.</div>
            </div>
        </div>
        <div class="card-ft">
            <div>
                @if(session('profile_ok'))
                    <span class="flash-ok">✓ {{ session('profile_ok') }}</span>
                @endif
            </div>
            <button class="btn-primary" wire:click="updateProfile">Save Changes</button>
        </div>
    </div>
    @endif

    {{-- Password Tab --}}
    @if($activeTab === 'password')
    <div class="settings-card">
        <div class="card-hd">
            <h2>Change Password</h2>
            <p>Use a strong password with at least 6 characters</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input wire:model="current_password" class="form-input" type="password" placeholder="Enter current password">
                @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input wire:model="new_password" class="form-input" type="password" placeholder="Min. 6 characters">
                    @error('new_password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input wire:model="confirm_password" class="form-input" type="password" placeholder="Repeat new password">
                    @error('confirm_password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="card-ft">
            <div>
                @if(session('password_ok'))
                    <span class="flash-ok">✓ {{ session('password_ok') }}</span>
                @endif
            </div>
            <button class="btn-primary" wire:click="updatePassword">Update Password</button>
        </div>
    </div>
    @endif

</div>
</div>