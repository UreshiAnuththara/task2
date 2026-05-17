<div>
@push('styles')
<style>
    /* ── Page Header ── */
    .um-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; flex-wrap:wrap; gap:12px; }
    .um-title  { font-size:20px; font-weight:800; color:#0f172a; }
    .um-sub    { font-size:13px; color:#64748b; margin-top:2px; }

    /* ── Flash ── */
    .flash { display:flex; align-items:center; gap:8px; padding:11px 14px; border-radius:8px; font-size:13px; font-weight:600; margin-bottom:18px; }
    .flash-ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
    .flash-err { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }

    /* ── Search + Button row ── */
    .um-toolbar { display:flex; align-items:center; gap:10px; margin-bottom:18px; flex-wrap:wrap; }
    .um-search  {
        flex:1; min-width:200px;
        padding:9px 14px 9px 36px;
        border:1.5px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#0f172a; background:#f8fafc;
        font-family:'Figtree',sans-serif; outline:none;
    }
    .um-search:focus { border-color:#2563eb; background:#fff; }
    .search-wrap { position:relative; flex:1; min-width:200px; }
    .search-icon { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }

    /* ── Buttons ── */
    .btn-primary { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#2563eb; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:'Figtree',sans-serif; cursor:pointer; white-space:nowrap; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-ghost  { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#f1f5f9; color:#374151; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; font-family:'Figtree',sans-serif; cursor:pointer; }
    .btn-ghost:hover { background:#e2e8f0; }
    .btn-danger { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#ef4444; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:'Figtree',sans-serif; cursor:pointer; }
    .btn-danger:hover { background:#dc2626; }
    .btn-sm { padding:6px 12px; font-size:12px; }

    /* ── Table ── */
    .um-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    .um-table { width:100%; border-collapse:collapse; }
    .um-table th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; background:#f8fafc; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
    .um-table td { padding:13px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#374151; vertical-align:middle; }
    .um-table tr:last-child td { border-bottom:none; }
    .um-table tr:hover td { background:#fafafa; }

    /* ── User cell ── */
    .user-cell { display:flex; align-items:center; gap:10px; }
    .user-avatar { width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid #e2e8f0; flex-shrink:0; }
    .user-name  { font-size:13px; font-weight:700; color:#0f172a; }
    .user-email { font-size:11px; color:#94a3b8; margin-top:1px; }

    /* ── Badges ── */
    .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:50px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; white-space:nowrap; }
    .badge-admin   { background:#fef3c7; color:#d97706; border:1px solid #fde68a; }
    .badge-user    { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
    .badge-custom  { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .badge-default { background:#fdf4ff; color:#9333ea; border:1px solid #e9d5ff; }
    .badge-shift-day   { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
    .badge-shift-night { background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }
    .badge-shift-none  { background:#f1f5f9; color:#94a3b8; border:1px solid #e2e8f0; }

    /* ── Actions ── */
    .action-wrap { display:flex; align-items:center; gap:6px; }
    .icon-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; cursor:pointer; color:#64748b; transition:all 0.12s; }
    .icon-btn:hover.edit-btn   { background:#eff6ff; border-color:#bfdbfe; color:#2563eb; }
    .icon-btn:hover.delete-btn { background:#fef2f2; border-color:#fecaca; color:#ef4444; }

    /* ── Empty state ── */
    .um-empty { padding:48px 20px; text-align:center; color:#94a3b8; }
    .um-empty svg { margin:0 auto 12px; display:block; }

    /* ── Modal ── */
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,0.45); z-index:200; display:flex; align-items:center; justify-content:center; padding:20px; }
    .modal-box { background:#fff; border-radius:14px; width:100%; max-width:480px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.2); }
    .modal-hd { padding:20px 22px 14px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; }
    .modal-title { font-size:16px; font-weight:800; color:#0f172a; }
    .modal-close { width:30px; height:30px; border-radius:6px; border:none; background:#f1f5f9; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; }
    .modal-close:hover { background:#e2e8f0; color:#0f172a; }
    .modal-body { padding:20px 22px; }
    .modal-ft { padding:14px 22px 20px; border-top:1px solid #f1f5f9; display:flex; gap:10px; justify-content:flex-end; }

    /* ── Form ── */
    .form-group { margin-bottom:15px; }
    .form-label { display:block; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; }
    .form-input { width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; color:#0f172a; font-family:'Figtree',sans-serif; outline:none; box-sizing:border-box; }
    .form-input:focus { border-color:#2563eb; background:#fff; }
    .form-error { font-size:11px; color:#dc2626; margin-top:4px; }
    .form-hint  { font-size:11px; color:#94a3b8; margin-top:4px; }

    /* Role pill row */
    .role-pills { display:flex; flex-wrap:wrap; gap:6px; margin-top:6px; }
    .role-pill { padding:5px 13px; border-radius:50px; font-size:12px; font-weight:600; border:1.5px solid #e2e8f0; background:#f8fafc; color:#374151; cursor:pointer; transition:all 0.12s; font-family:'Figtree',sans-serif; }
    .role-pill:hover { border-color:#2563eb; color:#2563eb; background:#eff6ff; }
    .role-pill.selected { border-color:#2563eb; color:#2563eb; background:#eff6ff; }
    .role-pill.admin-pill.selected { border-color:#d97706; color:#d97706; background:#fef3c7; }

    /* Shift pill row */
    .shift-pills { display:flex; gap:8px; margin-top:6px; flex-wrap:wrap; }
    .shift-pill { padding:7px 16px; border-radius:50px; font-size:12px; font-weight:600; border:1.5px solid #e2e8f0; background:#f8fafc; color:#374151; cursor:pointer; transition:all 0.12s; font-family:'Figtree',sans-serif; }
    .shift-pill:hover { border-color:#2563eb; color:#2563eb; background:#eff6ff; }
    .shift-pill.selected-none  { border-color:#94a3b8; color:#64748b; background:#f1f5f9; }
    .shift-pill.selected-day   { border-color:#d97706; color:#d97706; background:#fffbeb; }
    .shift-pill.selected-night { border-color:#7c3aed; color:#7c3aed; background:#f5f3ff; }

    /* Info notice */
    .notice-info { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; color:#2563eb; margin-top:4px; }
    .notice-warn { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; font-size:12px; color:#d97706; margin-top:4px; }

    /* ── Pagination ── */
    .pag-wrap { padding:14px 16px; border-top:1px solid #f1f5f9; }

    /* Delete modal */
    .del-modal-box { background:#fff; border-radius:14px; width:100%; max-width:380px; box-shadow:0 20px 60px rgba(0,0,0,0.2); padding:28px 26px; text-align:center; }
    .del-icon { width:52px; height:52px; background:#fef2f2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; }
    .del-title { font-size:16px; font-weight:800; color:#0f172a; margin-bottom:8px; }
    .del-body  { font-size:13px; color:#64748b; margin-bottom:22px; line-height:1.5; }
    .del-ft    { display:flex; gap:10px; justify-content:center; }
</style>
@endpush

<div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="flash flash-ok">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flash flash-err">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
@endif

{{-- Header --}}
<div class="um-header">
    <div>
        <div class="um-title">User Management</div>
        <div class="um-sub">Manage system users and their roles</div>
    </div>
    <button class="btn-primary" wire:click="openCreateModal">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add User
    </button>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:22px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Total Users</div>
        <div style="font-size:28px;font-weight:800;color:#0f172a;letter-spacing:-1px;">{{ $totalUsers }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Admins</div>
        <div style="font-size:28px;font-weight:800;color:#0f172a;letter-spacing:-1px;">{{ $totalAdmins }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Other Roles</div>
        <div style="font-size:28px;font-weight:800;color:#0f172a;letter-spacing:-1px;">{{ $totalOthers }}</div>
    </div>
</div>

{{-- Toolbar --}}
<div class="um-toolbar">
    <div class="search-wrap">
        <svg class="search-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
        <input wire:model.live.debounce.300ms="search" class="um-search" type="text" placeholder="Search users by name or email…">
    </div>
</div>

{{-- Table --}}
<div class="um-card">
    <table class="um-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Shift</th>
                <th>Joined</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="user-cell">
                        <img src="{{ $user->profileImageUrl() }}" alt="" class="user-avatar">
                        <div>
                            <div class="user-name">
                                {{ $user->name }}
                                @if($user->email === $defaultAdminEmail)
                                    <span style="font-size:10px;color:#9333ea;font-weight:700;"> (Default Admin)</span>
                                @endif
                            </div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($user->email === $defaultAdminEmail)
                        <span class="badge badge-default">★ Default Admin</span>
                    @elseif($user->role === 'admin')
                        <span class="badge badge-admin">Admin</span>
                    @elseif($user->role === 'user' || !$user->role)
                        <span class="badge badge-user">User</span>
                    @else
                        <span class="badge badge-custom">{{ $user->role }}</span>
                    @endif
                </td>
                <td>
                    @if($user->role === 'admin')
                        <span class="badge badge-shift-none">Unrestricted</span>
                    @elseif($user->shift === 'day')
                        <span class="badge badge-shift-day">☀ Day</span>
                    @elseif($user->shift === 'night')
                        <span class="badge badge-shift-night">🌙 Night</span>
                    @else
                        <span class="badge badge-shift-none">No Restriction</span>
                    @endif
                </td>
                <td style="color:#94a3b8;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div class="action-wrap" style="justify-content:flex-end;">
                        @if($user->email !== $defaultAdminEmail)
                        <button class="icon-btn edit-btn" wire:click="openEditModal({{ $user->id }})" title="Edit">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        @endif

                        @if($user->email !== $defaultAdminEmail)
                        <button class="icon-btn delete-btn" wire:click="openDeleteModal({{ $user->id }})" title="Delete">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="um-empty">
                        <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        No users found
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;padding:14px 16px;border-top:1px solid #f1f5f9;">
        <span style="font-size:13px;color:#64748b;">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
        </span>
        <div style="display:flex;align-items:center;gap:4px;">

            @if($users->onFirstPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;cursor:not-allowed;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <button wire:click="previousPage" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
            @endif

            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if($page == $users->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:7px;border:1px solid #2563eb;background:#2563eb;color:#fff;font-size:13px;font-weight:700;font-family:'Figtree',sans-serif;">
                        {{ $page }}
                    </span>
                @else
                    <button wire:click="gotoPage({{ $page }})" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:13px;font-weight:600;font-family:'Figtree',sans-serif;cursor:pointer;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                        {{ $page }}
                    </button>
                @endif
            @endforeach

            @if($users->hasMorePages())
                <button wire:click="nextPage" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;cursor:not-allowed;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif

        </div>
    </div>
    @endif
</div>

{{-- ══ Create / Edit Modal ══ --}}
@if($showModal)
<div class="modal-backdrop" wire:click.self="closeModal">
    <div class="modal-box">
        <div class="modal-hd">
            <span class="modal-title">{{ $isEditing ? 'Edit User' : 'Add New User' }}</span>
            <button class="modal-close" wire:click="closeModal">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form wire:submit.prevent="save">
        <div class="modal-body">

            {{-- Name --}}
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input wire:model="name" class="form-input" type="text" placeholder="Enter full name">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input wire:model="email" class="form-input" type="email" placeholder="email@company.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Password {{ $isEditing ? '(leave blank to keep)' : '' }}</label>
                <input wire:model="password" class="form-input" type="password" placeholder="{{ $isEditing ? 'Enter new password or leave blank' : 'Min. 6 characters' }}">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">Role</label>
                <input wire:model="role" class="form-input" type="text" placeholder="e.g. HR, Accounting, IT…">
                @error('role') <div class="form-error">{{ $message }}</div> @enderror

                {{-- Role suggestion pills --}}
                <div class="role-pills">
                    @foreach($roleSuggestions as $suggestion)
                        @if($suggestion === 'admin' && !$isDefaultAdmin)
                            @continue
                        @endif
                        <button type="button"
                            class="role-pill {{ $role === $suggestion ? 'selected' : '' }} {{ $suggestion === 'admin' ? 'admin-pill' : '' }}"
                            wire:click="$set('role', '{{ $suggestion }}')">
                            {{ $suggestion === 'admin' ? '★ Admin' : $suggestion }}
                        </button>
                    @endforeach
                </div>

                @if(!$isDefaultAdmin)
                    <div class="notice-info" style="margin-top:8px;">
                        ℹ️ Only the default administrator can assign the Admin role.
                    </div>
                @endif
            </div>

            {{-- Shift — hidden when role is admin --}}
            @if($role !== 'admin')
            <div class="form-group">
                <label class="form-label">Shift Restriction</label>
                <div class="shift-pills">
                    <button type="button"
                        class="shift-pill {{ $shift === '' ? 'selected-none' : '' }}"
                        wire:click="$set('shift', '')">
                        ∞ No Restriction
                    </button>
                    <button type="button"
                        class="shift-pill {{ $shift === 'day' ? 'selected-day' : '' }}"
                        wire:click="$set('shift', 'day')">
                        ☀ Day (8AM – 6PM)
                    </button>
                    <button type="button"
                        class="shift-pill {{ $shift === 'night' ? 'selected-night' : '' }}"
                        wire:click="$set('shift', 'night')">
                        🌙 Night (6PM – 8AM)
                    </button>
                </div>
                @error('shift') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">User can only log in during their assigned shift hours.</div>
            </div>
            @else
            <div class="notice-warn">
                🛡️ Admin users have unrestricted access — no shift limit applied.
            </div>
            @endif

            <div class="notice-info" style="margin-top:12px;">
                ℹ️ Users can upload their own profile photo from their <strong style="margin:0 2px;">Profile Settings</strong> page after logging in.
            </div>

        </div>
        <div class="modal-ft">
            <button type="button" class="btn-ghost" wire:click="closeModal">Cancel</button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Save Changes' : 'Create User' }}</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
        </form>

    </div>
</div>
@endif

{{-- ══ Delete Confirm Modal ══ --}}
@if($showDeleteModal)
<div class="modal-backdrop">
    <div class="del-modal-box">
        <div class="del-icon">
            <svg width="24" height="24" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div class="del-title">Delete User</div>
        <div class="del-body">
            Are you sure you want to delete <strong>{{ $deletingName }}</strong>?<br>This action cannot be undone.
        </div>
        <div class="del-ft">
            <button class="btn-ghost" wire:click="closeDeleteModal">Cancel</button>
            <button class="btn-danger" wire:click="confirmDelete" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="confirmDelete">Delete</span>
                <span wire:loading wire:target="confirmDelete">Deleting…</span>
            </button>
        </div>
    </div>
</div>
@endif

</div>