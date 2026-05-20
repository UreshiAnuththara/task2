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
    .btn-teal { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#0d9488; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:'Figtree',sans-serif; cursor:pointer; white-space:nowrap; }
    .btn-teal:hover { background:#0f766e; }

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
    .badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:50px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; white-space:nowrap; }
    .badge-admin     { background:#fef3c7; color:#d97706; border:1px solid #fde68a; }
    .badge-user      { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
    .badge-custom    { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .badge-default   { background:#fdf4ff; color:#9333ea; border:1px solid #e9d5ff; }
    .badge-time-day  { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
    .badge-time-night{ background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }
    .badge-time-none { background:#f1f5f9; color:#94a3b8; border:1px solid #e2e8f0; }

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
    .modal-box { background:#fff; border-radius:14px; width:100%; max-width:500px; max-height:92vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.2); }
    .modal-hd { padding:20px 22px 14px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; background:#fff; z-index:1; }
    .modal-title { font-size:16px; font-weight:800; color:#0f172a; }
    .modal-close { width:30px; height:30px; border-radius:6px; border:none; background:#f1f5f9; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; }
    .modal-close:hover { background:#e2e8f0; color:#0f172a; }
    .modal-body { padding:20px 22px; }
    .modal-ft { padding:14px 22px 20px; border-top:1px solid #f1f5f9; display:flex; gap:10px; justify-content:flex-end; position:sticky; bottom:0; background:#fff; z-index:1; }

    /* ── Form ── */
    .form-group { margin-bottom:15px; }
    .form-label { display:block; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; }
    .form-input { width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; color:#0f172a; font-family:'Figtree',sans-serif; outline:none; box-sizing:border-box; }
    .form-input:focus { border-color:#2563eb; background:#fff; }
    .form-error { font-size:11px; color:#dc2626; margin-top:4px; }

    /* ── Role Custom Dropdown ── */
    [x-cloak] { display:none !important; }
    .role-dd-btn {
        width:100%; padding:10px 12px; box-sizing:border-box;
        background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#0f172a; font-family:'Figtree',sans-serif;
        outline:none; cursor:pointer; text-align:left;
        display:flex; align-items:center; justify-content:space-between; gap:8px;
        transition:border-color 0.15s, background 0.15s;
    }
    .role-dd-btn:hover, .role-dd-btn.open { border-color:#2563eb; background:#fff; }
    .role-dd-btn svg { flex-shrink:0; color:#94a3b8; transition:transform 0.18s; }
    .role-dd-list {
        position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:600;
        background:#fff; border:1.5px solid #e2e8f0; border-radius:10px;
        box-shadow:0 8px 32px rgba(15,23,42,0.13);
        max-height:117px; /* ~3 items × 39px */
        overflow-y:auto;
        scrollbar-width:thin; scrollbar-color:#cbd5e1 #f8fafc;
    }
    .role-dd-list::-webkit-scrollbar { width:5px; }
    .role-dd-list::-webkit-scrollbar-track { background:#f8fafc; }
    .role-dd-list::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }
    .role-dd-item {
        padding:9px 13px; font-size:13px; font-family:'Figtree',sans-serif;
        color:#374151; cursor:pointer; display:flex; align-items:center;
        gap:8px; border-bottom:1px solid #f1f5f9; transition:background 0.1s;
    }
    .role-dd-item:last-child { border-bottom:none; }
    .role-dd-item:hover { background:#eff6ff; color:#2563eb; }
    .role-dd-item.active { background:#eff6ff; color:#2563eb; font-weight:700; }
    .role-dd-item.admin-item.active { background:#fef3c7; color:#d97706; }
    .role-dd-item.admin-item:hover { background:#fef3c7; color:#d97706; }
    .role-dd-name { font-weight:600; }
    .role-dd-desc { font-size:11px; color:#94a3b8; margin-left:auto; font-style:italic; padding-left:8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px; }

    /* ── Role Login Time Preview Box ── */
    .role-time-preview {
        margin-top:10px;
        border-radius:10px;
        padding:13px 15px;
        font-size:12px;
        font-weight:600;
        display:flex;
        align-items:flex-start;
        gap:10px;
        line-height:1.5;
    }
    .role-time-preview.has-time-day   { background:#fffbeb; border:1px solid #fde68a; color:#92400e; }
    .role-time-preview.has-time-night { background:#f5f3ff; border:1px solid #ddd6fe; color:#5b21b6; }
    .role-time-preview.no-time        { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
    .role-time-preview.admin-role     { background:#fffbeb; border:1px solid #fde68a; color:#92400e; }

    /* ── Notices ── */
    .notice-info { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; color:#2563eb; margin-top:4px; }
    .notice-warn { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; font-size:12px; color:#d97706; margin-top:4px; }

    /* ── Delete modal ── */
    .del-modal-box { background:#fff; border-radius:14px; width:100%; max-width:380px; box-shadow:0 20px 60px rgba(0,0,0,0.2); padding:28px 26px; text-align:center; }
    .del-icon  { width:52px; height:52px; background:#fef2f2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; }
    .del-title { font-size:16px; font-weight:800; color:#0f172a; margin-bottom:8px; }
    .del-body  { font-size:13px; color:#64748b; margin-bottom:22px; line-height:1.5; }
    .del-ft    { display:flex; gap:10px; justify-content:center; }

    /* ── Pagination ── */
    .pag-wrap { padding:14px 16px; border-top:1px solid #f1f5f9; }
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
        <div class="um-sub">Manage system users and assign roles</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        {{-- <a href="{{ route('roles.index') }}" wire:navigate
            class="btn-teal">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Manage Roles
        </a> --}}
        <button class="btn-primary" wire:click="openCreateModal">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add User
        </button>
    </div>
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
                <th>Login Access</th>
                <th>Joined</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            @php
                $userRoleModel  = \App\Models\UserRole::where('name', $user->role)->first();
                $hasTime        = $userRoleModel && $userRoleModel->hasLoginRestriction();
                $isOvernight    = $hasTime && $userRoleModel->login_start >= $userRoleModel->login_end;
                $fmtTime        = fn($t) => $t ? \Carbon\Carbon::createFromFormat('H:i', $t)->format('g:i A') : null;
            @endphp
            <tr>
                {{-- User --}}
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

                {{-- Role --}}
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

                {{-- Login Access (from role) --}}
                <td>
                    @if($user->role === 'admin')
                        <span class="badge badge-time-none">Unrestricted</span>
                    @elseif($hasTime && $isOvernight)
                        <span class="badge badge-time-night">
                            🌙 {{ $fmtTime($userRoleModel->login_start) }} – {{ $fmtTime($userRoleModel->login_end) }}
                        </span>
                    @elseif($hasTime)
                        <span class="badge badge-time-day">
                            ☀ {{ $fmtTime($userRoleModel->login_start) }} – {{ $fmtTime($userRoleModel->login_end) }}
                        </span>
                    @else
                        <span class="badge badge-time-none">No Restriction</span>
                    @endif
                </td>

                <td style="color:#94a3b8;">{{ $user->created_at->format('d M Y') }}</td>

                {{-- Actions --}}
                <td>
                    <div class="action-wrap" style="justify-content:flex-end;">
                        @if($user->email !== $defaultAdminEmail)
                        <button class="icon-btn edit-btn" wire:click="openEditModal({{ $user->id }})" title="Edit">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
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
                <button wire:click="previousPage" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
            @endif
            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if($page == $users->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:7px;border:1px solid #2563eb;background:#2563eb;color:#fff;font-size:13px;font-weight:700;font-family:'Figtree',sans-serif;">{{ $page }}</span>
                @else
                    <button wire:click="gotoPage({{ $page }})" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:13px;font-weight:600;font-family:'Figtree',sans-serif;cursor:pointer;">{{ $page }}</button>
                @endif
            @endforeach
            @if($users->hasMorePages())
                <button wire:click="nextPage" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer;">
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

</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- Create / Edit User Modal                          --}}
{{-- ══════════════════════════════════════════════════ --}}
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
                <input wire:model="password" class="form-input" type="password"
                    placeholder="{{ $isEditing ? 'Enter new password or leave blank' : 'Min. 6 characters' }}">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">Role / Department</label>

                @php
                    $editingAdminSelf = $isEditing && $editingIsAdmin && !$isDefaultAdmin;
                @endphp

                @if($editingAdminSelf)
                    {{-- Non-default-admin editing an admin user — role stays locked --}}
                    <div style="display:flex;align-items:center;gap:10px;margin-top:6px;padding:10px 14px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;">
                        <span style="font-size:13px;font-weight:700;color:#d97706;">★ Admin</span>
                        <span style="font-size:12px;color:#94a3b8;">🔒 Only the default administrator can change admin roles.</span>
                    </div>
                @else
                    {{-- Custom dropdown: scroll after 3 items, $wire.set triggers Livewire re-render for preview --}}
                    <div style="position:relative;" x-data="{ open: false }" @click.outside="open = false">

                        {{-- Trigger button --}}
                        <button type="button"
                            class="role-dd-btn"
                            :class="{ 'open': open }"
                            @click="open = !open">
                            <span>{{ $role === 'admin' ? '★ Admin' : ($role ?: '— select a role —') }}</span>
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
                                :style="open ? 'transform:rotate(180deg)' : ''">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        {{-- Dropdown list: max-height shows ~3 items then scrolls --}}
                        <div class="role-dd-list" x-show="open" x-cloak>
                            @foreach($roles as $r)
                                @if($r->name === 'admin' && !$isDefaultAdmin) @continue @endif
                                <div class="role-dd-item {{ $role === $r->name ? 'active' : '' }} {{ $r->name === 'admin' ? 'admin-item' : '' }}"
                                     wire:click="$set('role', '{{ $r->name }}')"
                                     @click="open = false">
                                    <span class="role-dd-name">{{ $r->name === 'admin' ? '★ Admin' : $r->name }}</span>
                                    @if($r->description)
                                        <span class="role-dd-desc">{{ $r->description }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                    @if(!$isDefaultAdmin)
                        <div class="notice-info" style="margin-top:8px;">
                            ℹ️ Only the default administrator can assign the Admin role.
                        </div>
                    @endif
                @endif

                {{-- ── LOGIN ACCESS TIME PREVIEW (always live, based on selected role) ── --}}
                @if($role === 'admin')
                    <div class="role-time-preview admin-role" style="margin-top:10px;">
                        <span style="font-size:16px;">🛡️</span>
                        <div>
                            <strong>Admin — Unrestricted 24-Hour Access</strong><br>
                            <span style="font-weight:400;opacity:0.8;">Admin users can log in at any time without restriction.</span>
                        </div>
                    </div>
                @elseif($selectedRoleModel && $selectedRoleModel->hasLoginRestriction())
                    @php
                        $selStart     = $selectedRoleModel->login_start;
                        $selEnd       = $selectedRoleModel->login_end;
                        $selOvernight = $selStart >= $selEnd;
                        $fmtSel       = fn($t) => \Carbon\Carbon::createFromFormat('H:i', $t)->format('g:i A');
                    @endphp
                    <div class="role-time-preview {{ $selOvernight ? 'has-time-night' : 'has-time-day' }}" style="margin-top:10px;">
                        <span style="font-size:16px;">{{ $selOvernight ? '🌙' : '☀️' }}</span>
                        <div>
                            <strong>Login Allowed: {{ $fmtSel($selStart) }} – {{ $fmtSel($selEnd) }}
                            {{ $selOvernight ? '(overnight)' : '' }}</strong><br>
                            <span style="font-weight:400;opacity:0.85;">
                                Users assigned to <strong>{{ $selectedRoleModel->name }}</strong>
                                can only log in during these hours (Sri Lanka time).
                                @if($selectedRoleModel->description)
                                    <br><em>{{ $selectedRoleModel->description }}</em>
                                @endif
                            </span>
                        </div>
                    </div>
                @elseif($selectedRoleModel)
                    <div class="role-time-preview no-time" style="margin-top:10px;">
                        <span style="font-size:16px;">✅</span>
                        <div>
                            <strong>No Login Time Restriction</strong><br>
                            <span style="font-weight:400;opacity:0.85;">
                                Users assigned to <strong>{{ $selectedRoleModel->name }}</strong>
                                can log in at any time.
                                @if($selectedRoleModel->description)
                                    <em>{{ $selectedRoleModel->description }}</em>
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

            </div>

            <div class="notice-info">
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

{{-- ══════════════════════════════════════════════════ --}}
{{-- Delete Confirm Modal                              --}}
{{-- ══════════════════════════════════════════════════ --}}
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