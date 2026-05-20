<div>
@push('styles')
<style>
    /* ── Page Header ── */
    .rm-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; flex-wrap:wrap; gap:12px; }
    .rm-title  { font-size:20px; font-weight:800; color:#0f172a; }
    .rm-sub    { font-size:13px; color:#64748b; margin-top:2px; }

    /* ── Flash ── */
    .flash { display:flex; align-items:center; gap:8px; padding:11px 14px; border-radius:8px; font-size:13px; font-weight:600; margin-bottom:18px; }
    .flash-ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
    .flash-err { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }

    /* ── Buttons ── */
    .btn-primary { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#2563eb; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:'Figtree',sans-serif; cursor:pointer; white-space:nowrap; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-ghost  { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#f1f5f9; color:#374151; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; font-family:'Figtree',sans-serif; cursor:pointer; }
    .btn-ghost:hover { background:#e2e8f0; }
    .btn-danger { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#ef4444; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; font-family:'Figtree',sans-serif; cursor:pointer; }
    .btn-danger:hover { background:#dc2626; }
    .btn-sm  { padding:6px 12px; font-size:12px; }

    /* ── Table ── */
    .rm-card  { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
    .rm-table { width:100%; border-collapse:collapse; }
    .rm-table th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; background:#f8fafc; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
    .rm-table td { padding:14px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#374151; vertical-align:middle; }
    .rm-table tr:last-child td { border-bottom:none; }
    .rm-table tr:hover td { background:#fafafa; }

    /* ── Badges ── */
    .badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:50px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; white-space:nowrap; }
    .badge-sys      { background:#fef3c7; color:#d97706; border:1px solid #fde68a; }
    .badge-custom   { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .badge-time     { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
    .badge-notime   { background:#f1f5f9; color:#94a3b8; border:1px solid #e2e8f0; }
    .badge-overnight{ background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; }
    .badge-users    { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; }

    /* ── Actions ── */
    .action-wrap { display:flex; align-items:center; gap:6px; }
    .icon-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; cursor:pointer; color:#64748b; }
    .icon-btn:hover.edit-btn   { background:#eff6ff; border-color:#bfdbfe; color:#2563eb; }
    .icon-btn:hover.delete-btn { background:#fef2f2; border-color:#fecaca; color:#ef4444; }

    /* ── Empty ── */
    .rm-empty { padding:48px 20px; text-align:center; color:#94a3b8; }

    /* ── Modal ── */
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,0.45); z-index:200; display:flex; align-items:center; justify-content:center; padding:20px; }
    .modal-box { background:#fff; border-radius:14px; width:100%; max-width:480px; max-height:92vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.2); }
    .modal-hd  { padding:20px 22px 14px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; background:#fff; z-index:1; }
    .modal-title { font-size:16px; font-weight:800; color:#0f172a; }
    .modal-close { width:30px; height:30px; border-radius:6px; border:none; background:#f1f5f9; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b; }
    .modal-close:hover { background:#e2e8f0; color:#0f172a; }
    .modal-body { padding:20px 22px; }
    .modal-ft  { padding:14px 22px 20px; border-top:1px solid #f1f5f9; display:flex; gap:10px; justify-content:flex-end; position:sticky; bottom:0; background:#fff; z-index:1; }

    /* ── Form ── */
    .form-group { margin-bottom:16px; }
    .form-label { display:block; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; }
    .form-input { width:100%; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; color:#0f172a; font-family:'Figtree',sans-serif; outline:none; box-sizing:border-box; }
    .form-input:focus { border-color:#2563eb; background:#fff; }
    .form-error { font-size:11px; color:#dc2626; margin-top:4px; }
    .form-hint  { font-size:11px; color:#94a3b8; margin-top:4px; }

    /* ── Time Restriction Toggle ── */
    .time-toggle-wrap {
        display:flex; gap:0; border:1.5px solid #e2e8f0; border-radius:9px;
        overflow:hidden; margin-top:6px; background:#f8fafc;
    }
    .time-toggle-btn {
        flex:1; padding:10px 14px; font-size:13px; font-weight:700;
        font-family:'Figtree',sans-serif; border:none; background:transparent;
        color:#64748b; cursor:pointer; transition:all 0.15s;
        display:flex; align-items:center; justify-content:center; gap:7px;
    }
    .time-toggle-btn:first-child { border-right:1.5px solid #e2e8f0; }
    .time-toggle-btn.active-none   { background:#f0fdf4; color:#15803d; }
    .time-toggle-btn.active-custom { background:#eff6ff; color:#2563eb; }

    /* ── Time Inputs ── */
    .time-box { margin-top:12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; padding:16px; display:flex; flex-direction:column; gap:12px; }
    .time-row { display:flex; align-items:center; gap:12px; }
    .time-lbl { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.6px; width:60px; flex-shrink:0; }
    input[type="time"].t-input { padding:9px 12px; background:#fff; border:1.5px solid #e2e8f0; border-radius:8px; font-size:14px; font-weight:700; color:#0f172a; font-family:'Figtree',sans-serif; outline:none; width:150px; }
    input[type="time"].t-input:focus { border-color:#2563eb; }

    /* ── Preview labels ── */
    .prev-day     { display:flex; align-items:center; gap:8px; padding:9px 13px; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; font-size:12px; color:#d97706; font-weight:600; margin-top:10px; }
    .prev-night   { display:flex; align-items:center; gap:8px; padding:9px 13px; background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; font-size:12px; color:#7c3aed; font-weight:600; margin-top:10px; }
    .prev-warn    { display:flex; align-items:center; gap:8px; padding:9px 13px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; font-size:12px; color:#dc2626; font-weight:600; margin-top:10px; }
    .prev-ok      { display:flex; align-items:center; gap:8px; padding:9px 13px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; font-size:12px; color:#15803d; font-weight:600; margin-top:8px; }
    .notice-info  { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; color:#2563eb; margin-top:4px; }

    /* ── Search ── */
    .rm-toolbar { display:flex; align-items:center; gap:10px; margin-bottom:18px; flex-wrap:wrap; }
    .rm-search {
        flex:1; min-width:200px;
        padding:9px 14px 9px 36px;
        border:1.5px solid #e2e8f0; border-radius:8px;
        font-size:13px; color:#0f172a; background:#f8fafc;
        font-family:'Figtree',sans-serif; outline:none;
    }
    .rm-search:focus { border-color:#2563eb; background:#fff; }
    .rm-search-wrap { position:relative; flex:1; min-width:200px; }
    .rm-search-icon { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
    .del-box   { background:#fff; border-radius:14px; width:100%; max-width:380px; box-shadow:0 20px 60px rgba(0,0,0,0.2); padding:28px 26px; text-align:center; }
    .del-icon  { width:52px; height:52px; background:#fef2f2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; }
    .del-title { font-size:16px; font-weight:800; color:#0f172a; margin-bottom:8px; }
    .del-body  { font-size:13px; color:#64748b; margin-bottom:22px; line-height:1.5; }
    .del-ft    { display:flex; gap:10px; justify-content:center; }
</style>
@endpush

<div>

{{-- Flash --}}
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
<div class="rm-header">
    <div>
        <div class="rm-title">Role Management</div>
        <div class="rm-sub">Manage roles and configure login access time windows</div>
    </div>
    <button class="btn-primary" wire:click="openCreateModal">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Role
    </button>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:22px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Total Roles</div>
        <div style="font-size:28px;font-weight:800;color:#0f172a;letter-spacing:-1px;">{{ $roles->total() }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">With Time Limit</div>
        <div style="font-size:28px;font-weight:800;color:#2563eb;letter-spacing:-1px;">{{ $roles->getCollection()->filter(fn($r) => $r->hasLoginRestriction())->count() }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">System Roles</div>
        <div style="font-size:28px;font-weight:800;color:#d97706;letter-spacing:-1px;">{{ $roles->getCollection()->filter(fn($r) => $r->is_system)->count() }}</div>
    </div>
</div>

{{-- Toolbar --}}
<div class="rm-toolbar">
    <div class="rm-search-wrap">
        <svg class="rm-search-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
        <input wire:model.live.debounce.300ms="search" class="rm-search" type="text" placeholder="Search roles by name or description…">
    </div>
</div>

{{-- Roles Table --}}
<div class="rm-card">
    <table class="rm-table">
        <thead>
            <tr>
                <th>Role Name</th>
                <th>Description</th>
                <th>Login Access Window</th>
                <th>Users</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
            @php
                $hasTime    = $role->hasLoginRestriction();
                $isOvernight= $hasTime && $role->login_start >= $role->login_end;
                $userCount  = $userCounts[$role->name] ?? 0;
                $fmtTime    = fn($t) => $t ? \Carbon\Carbon::createFromFormat('H:i', $t)->format('g:i A') : null;
            @endphp
            <tr>
                {{-- Role Name --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:13px;font-weight:700;color:#0f172a;">{{ $role->name }}</span>
                        @if($role->is_system)
                            <span class="badge badge-sys">★ System</span>
                        @else
                            <span class="badge badge-custom">Custom</span>
                        @endif
                    </div>
                </td>

                {{-- Description --}}
                <td style="color:#64748b;max-width:200px;">
                    {{ $role->description ?: '—' }}
                </td>

                {{-- Login Access Window --}}
                <td>
                    @if(!$hasTime)
                        <span class="badge badge-notime">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                            No Restriction
                        </span>
                    @elseif($isOvernight)
                        <span class="badge badge-overnight" title="{{ $fmtTime($role->login_start) }} – {{ $fmtTime($role->login_end) }}">
                            🌙 {{ $fmtTime($role->login_start) }} – {{ $fmtTime($role->login_end) }}
                        </span>
                    @else
                        <span class="badge badge-time" title="{{ $fmtTime($role->login_start) }} – {{ $fmtTime($role->login_end) }}">
                            ☀ {{ $fmtTime($role->login_start) }} – {{ $fmtTime($role->login_end) }}
                        </span>
                    @endif
                </td>

                {{-- Users --}}
                <td>
                    @if($userCount > 0)
                        <span class="badge badge-users">{{ $userCount }} {{ Str::plural('user', $userCount) }}</span>
                    @else
                        <span style="color:#cbd5e1;font-size:12px;">No users</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td>
                    <div class="action-wrap" style="justify-content:flex-end;">
                        @if(!$role->is_system)
                            <button class="icon-btn edit-btn" wire:click="openEditModal({{ $role->id }})" title="Edit">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button class="icon-btn delete-btn" wire:click="openDeleteModal({{ $role->id }})" title="Delete">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @else
                            <span style="font-size:11px;color:#cbd5e1;padding:0 8px;">Protected</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="rm-empty">
                        <svg width="40" height="40" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div style="margin-top:8px;">No roles found</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($roles->hasPages())
    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;padding:14px 16px;border-top:1px solid #f1f5f9;">
        <span style="font-size:13px;color:#64748b;">
            Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} results
        </span>
        <div style="display:flex;align-items:center;gap:4px;">
            @if($roles->onFirstPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;cursor:not-allowed;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <button wire:click="previousPage" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
            @endif
            @foreach($roles->getUrlRange(1, $roles->lastPage()) as $page => $url)
                @if($page == $roles->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:7px;border:1px solid #2563eb;background:#2563eb;color:#fff;font-size:13px;font-weight:700;font-family:'Figtree',sans-serif;">{{ $page }}</span>
                @else
                    <button wire:click="gotoPage({{ $page }})" style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:13px;font-weight:600;font-family:'Figtree',sans-serif;cursor:pointer;">{{ $page }}</button>
                @endif
            @endforeach
            @if($roles->hasMorePages())
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
{{-- Create / Edit Role Modal                          --}}
{{-- ══════════════════════════════════════════════════ --}}
@if($showModal)
<div class="modal-backdrop" wire:click.self="closeModal">
    <div class="modal-box">
        <div class="modal-hd">
            <span class="modal-title">{{ $isEditing ? 'Edit Role' : 'Add New Role' }}</span>
            <button class="modal-close" wire:click="closeModal">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form wire:submit.prevent="save">
        <div class="modal-body">

            {{-- Role Name --}}
            <div class="form-group">
                <label class="form-label">Role Name</label>
                <input wire:model="name" class="form-input" type="text" placeholder="e.g. Cashier, Warehouse, IT…">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label class="form-label">Description <span style="color:#94a3b8;font-weight:400;text-transform:none;">(optional)</span></label>
                <input wire:model="description" class="form-input" type="text" placeholder="Brief description of this role…">
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Login Access Time --}}
            <div class="form-group">
                <label class="form-label">Login Access Time</label>

                <div class="time-toggle-wrap">
                    <button type="button"
                        class="time-toggle-btn {{ !$hasTimeLimit ? 'active-none' : '' }}"
                        wire:click="$set('hasTimeLimit', false)">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
                        No Restriction (24h)
                    </button>
                    <button type="button"
                        class="time-toggle-btn {{ $hasTimeLimit ? 'active-custom' : '' }}"
                        wire:click="$set('hasTimeLimit', true)">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Set Time Window
                    </button>
                </div>

                @if($hasTimeLimit)
                <div class="time-box">
                    <div class="time-row">
                        <div class="time-lbl">Start</div>
                        <input type="time" class="t-input" wire:model.live="loginStart">
                        @error('loginStart') <span style="font-size:11px;color:#dc2626;">{{ $message }}</span> @enderror
                    </div>
                    <div class="time-row">
                        <div class="time-lbl">End</div>
                        <input type="time" class="t-input" wire:model.live="loginEnd">
                        @error('loginEnd') <span style="font-size:11px;color:#dc2626;">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Live Preview --}}
                @if($previewIsSame)
                    <div class="prev-warn">⚠️ Start and end time cannot be the same.</div>
                @elseif($previewIsOvernight)
                    <div class="prev-night">
                        🌙 {{ \Carbon\Carbon::createFromFormat('H:i', $loginStart)->format('g:i A') }}
                        to
                        {{ \Carbon\Carbon::createFromFormat('H:i', $loginEnd)->format('g:i A') }}
                        — overnight window (crosses midnight). Users can only login within these hours.
                    </div>
                @else
                    <div class="prev-day">
                        ☀ {{ \Carbon\Carbon::createFromFormat('H:i', $loginStart)->format('g:i A') }}
                        to
                        {{ \Carbon\Carbon::createFromFormat('H:i', $loginEnd)->format('g:i A') }}
                        — same-day window. Users can only login within these hours.
                    </div>
                @endif

                @else
                    <div class="prev-ok" style="margin-top:8px;">✅ Users with this role can log in at any time — no restriction.</div>
                @endif

            </div>

            <div class="notice-info">
                ℹ️ This login time window applies to <strong>all users</strong> assigned to this role. Admins are always unrestricted.
            </div>

        </div>
        <div class="modal-ft">
            <button type="button" class="btn-ghost" wire:click="closeModal">Cancel</button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Save Changes' : 'Create Role' }}</span>
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
    <div class="del-box">
        <div class="del-icon">
            <svg width="24" height="24" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div class="del-title">Delete Role</div>
        <div class="del-body">
            Are you sure you want to delete the <strong>{{ $deletingName }}</strong> role?<br>
            This action cannot be undone.
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