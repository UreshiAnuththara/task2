<div>
@push('styles')
<style>
    /* ── Dark Theme Variables (inherits from layout) ── */
    :root {
        --bg: #0d1117;
        --surface: #111827;
        --surface-2: #1a2234;
        --border: #1e2d45;
        --text: #f1f5f9;
        --text-muted: #64748b;
        --accent: #2563eb;
        --accent-hover: #1d4ed8;
        --accent-soft: rgba(37,99,235,0.12);
        --danger: #ef4444;
        --success: #22c55e;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.3px; }
    .page-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px;
        background: var(--accent);
        color: #fff;
        border: none; border-radius: 9px;
        font-size: 14px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
        text-decoration: none;
    }
    .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }

    .btn-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px;
        background: var(--surface-2);
        color: var(--text);
        border: 1px solid var(--border); border-radius: 9px;
        font-size: 14px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-secondary:hover { background: #243050; }

    /* ── Flash ── */
    .flash-success {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px;
        background: rgba(34,197,94,0.1);
        border: 1px solid rgba(34,197,94,0.25);
        border-radius: 10px;
        color: #86efac;
        font-size: 13px; font-weight: 600;
        margin-bottom: 20px;
    }

    .flash-error {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px;
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.25);
        border-radius: 10px;
        color: #fca5a5;
        font-size: 13px; font-weight: 600;
        margin-bottom: 20px;
    }

    /* ── Info Cards ── */
    .info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 20px;
    }

    .info-card .ic-label { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 6px; }
    .info-card .ic-value { font-size: 26px; font-weight: 800; color: var(--text); letter-spacing: -1px; }
    .info-card .ic-sub   { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    /* ── Search ── */
    .search-wrap {
        position: relative;
        max-width: 320px;
        margin-bottom: 20px;
    }

    .search-wrap svg {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; color: var(--text-muted);
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: 9px;
        color: var(--text);
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input:focus { border-color: var(--accent); }
    .search-input::placeholder { color: var(--text-muted); }

    /* ── Table ── */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
    }

    .data-table { width: 100%; border-collapse: collapse; }

    .data-table thead { background: var(--surface-2); }
    .data-table thead th {
        padding: 13px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
    }

    .data-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.02); }

    .data-table td { padding: 14px 16px; font-size: 14px; color: var(--text); vertical-align: middle; }

    /* ── User row ── */
    .user-row-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border);
    }

    .u-name-cell { font-weight: 700; color: var(--text); }
    .u-email-cell { font-size: 13px; color: var(--text-muted); }

    .role-badge {
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-badge.admin { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .role-badge.user  { background: rgba(96,165,250,0.12); color: #60a5fa; border: 1px solid rgba(96,165,250,0.25); }

    .default-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px;
        border-radius: 50px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(34,197,94,0.1);
        color: #86efac;
        border: 1px solid rgba(34,197,94,0.25);
        margin-left: 6px;
    }

    /* ── Action Buttons ── */
    .action-btns { display: flex; gap: 6px; align-items: center; }

    .btn-action {
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.15s;
        display: inline-flex; align-items: center; gap: 4px;
    }

    .btn-edit {
        background: rgba(37,99,235,0.1);
        color: #60a5fa;
        border-color: rgba(37,99,235,0.2);
    }
    .btn-edit:hover { background: rgba(37,99,235,0.2); }

    .btn-delete {
        background: rgba(239,68,68,0.08);
        color: #f87171;
        border-color: rgba(239,68,68,0.2);
    }
    .btn-delete:hover { background: rgba(239,68,68,0.15); }

    .btn-locked {
        background: var(--surface-2);
        color: var(--text-muted);
        border-color: var(--border);
        cursor: not-allowed;
        opacity: 0.5;
    }

    /* ── Modal ── */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 200;
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
    }

    .modal {
        background: #111827;
        border: 1px solid var(--border);
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0,0,0,0.6);
    }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 22px 24px 16px;
        border-bottom: 1px solid var(--border);
    }

    .modal-title { font-size: 18px; font-weight: 800; color: var(--text); }

    .modal-close {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: var(--surface-2);
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted);
        transition: background 0.15s;
    }
    .modal-close:hover { background: #243050; color: var(--text); }

    .modal-body { padding: 22px 24px; }

    .form-group { margin-bottom: 18px; }

    .form-label {
        display: block;
        font-size: 12px; font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 0.8px;
        margin-bottom: 7px;
    }

    .form-input, .form-select {
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

    .form-input:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    .form-select option { background: #1a2234; }
    .form-error { font-size: 12px; color: #f87171; margin-top: 5px; }
    .form-hint  { font-size: 12px; color: var(--text-muted); margin-top: 5px; }

    .modal-footer {
        display: flex; justify-content: flex-end; gap: 10px;
        padding: 16px 24px 22px;
        border-top: 1px solid var(--border);
    }

    /* ── Delete Modal ── */
    .delete-modal { max-width: 420px; }

    .delete-icon {
        width: 56px; height: 56px;
        border-radius: 50%;
        background: rgba(239,68,68,0.12);
        border: 2px solid rgba(239,68,68,0.2);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
    }

    .delete-modal .modal-body { text-align: center; padding-bottom: 8px; }
    .delete-title { font-size: 18px; font-weight: 800; color: var(--text); margin-bottom: 8px; }
    .delete-desc  { font-size: 14px; color: var(--text-muted); line-height: 1.6; }
    .delete-name  { font-weight: 700; color: #f87171; }

    .btn-danger {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px;
        background: var(--danger);
        color: #fff;
        border: none; border-radius: 9px;
        font-size: 14px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-danger:hover { background: #dc2626; }

    /* ── Roles Info Section ── */
    .roles-info-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .roles-info-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .role-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }
    .role-item:last-child { border-bottom: none; padding-bottom: 0; }

    .role-icon {
        width: 38px; height: 38px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .role-icon.admin { background: rgba(245,158,11,0.1); }
    .role-icon.user  { background: rgba(96,165,250,0.1); }

    .role-item-name { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
    .role-item-desc { font-size: 12px; color: var(--text-muted); line-height: 1.5; }

    /* ── Pagination ── */
    .pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--border); }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state svg { width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3; }
    .empty-state p { font-size: 15px; font-weight: 600; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .info-row { grid-template-columns: repeat(2, 1fr); }
        .data-table td:nth-child(3),
        .data-table th:nth-child(3) { display: none; }
    }
</style>
@endpush

{{-- ═══ Page Header ═══ --}}
<div class="page-header">
    <div>
        <div class="page-title">System Users</div>
        <div class="page-subtitle">Manage user accounts and access roles</div>
    </div>
    <button wire:click="openCreateModal" class="btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Add User
    </button>
</div>

{{-- ═══ Flash Messages ═══ --}}
@if (session()->has('success'))
    <div class="flash-success">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="flash-error">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- ═══ Info Cards ═══ --}}
<div class="info-row">
    <div class="info-card">
        <div class="ic-label">Total Users</div>
        <div class="ic-value">{{ \App\Models\User::count() }}</div>
        <div class="ic-sub">All accounts</div>
    </div>
    <div class="info-card">
        <div class="ic-label">Administrators</div>
        <div class="ic-value">{{ \App\Models\User::where('role','admin')->count() }}</div>
        <div class="ic-sub">Admin role</div>
    </div>
    <div class="info-card">
        <div class="ic-label">Regular Users</div>
        <div class="ic-value">{{ \App\Models\User::where('role','user')->count() }}</div>
        <div class="ic-sub">User role</div>
    </div>
</div>

{{-- ═══ Roles Info ═══ --}}
<div class="roles-info-card">
    <div class="roles-info-title">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        System Roles
    </div>

    <div class="role-item">
        <div class="role-icon admin">⭐</div>
        <div>
            <div class="role-item-name">Administrator <span class="role-badge admin">Admin</span></div>
            <div class="role-item-desc">Full access to all features including User Management. Can add, edit, and delete any user. Can modify supplier records. The default administrator account is protected and cannot be deleted.</div>
        </div>
    </div>

    <div class="role-item">
        <div class="role-icon user">👤</div>
        <div>
            <div class="role-item-name">Regular User <span class="role-badge user">User</span></div>
            <div class="role-item-desc">Access to Supplier Management only. Can view, add, edit, and print suppliers. Can update their own profile and change their password. Cannot access User Management or delete other accounts.</div>
        </div>
    </div>
</div>

{{-- ═══ Search ═══ --}}
<div class="search-wrap">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <input wire:model.live.debounce.300ms="search"
           class="search-input"
           type="text"
           placeholder="Search users by name or email…">
</div>

{{-- ═══ Users Table ═══ --}}
<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                {{-- Avatar + Name --}}
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img src="{{ $user->profileImageUrl() }}"
                             alt="{{ $user->name }}"
                             class="user-row-avatar">
                        <div>
                            <div class="u-name-cell">
                                {{ $user->name }}
                                @if($user->email === $defaultAdminEmail)
                                    <span class="default-badge">✓ Default</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>

                {{-- Email --}}
                <td class="u-email-cell">{{ $user->email }}</td>

                {{-- Role --}}
                <td>
                    <span class="role-badge {{ $user->role }}">
                        {{ $user->role === 'admin' ? '★ Admin' : '● User' }}
                    </span>
                </td>

                {{-- Joined --}}
                <td style="font-size:13px;color:var(--text-muted);">
                    {{ $user->created_at->format('d M Y') }}
                </td>

                {{-- Actions --}}
                <td>
                    <div class="action-btns" style="justify-content:flex-end;">
                        <button wire:click="openEditModal({{ $user->id }})" class="btn-action btn-edit">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>

                        @if($user->email !== $defaultAdminEmail)
                            <button wire:click="openDeleteModal({{ $user->id }})" class="btn-action btn-delete">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        @else
                            <span class="btn-action btn-locked">
                                🔒 Protected
                            </span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p>No users found</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if ($users->hasPages())
        <div class="pagination-wrap">
            {{ $users->links() }}
        </div>
    @endif
</div>


{{-- ═══════════════════════════════════════
     CREATE / EDIT USER MODAL
═══════════════════════════════════════ --}}
@if ($showModal)
<div class="modal-backdrop" wire:click.self="closeModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">{{ $isEditing ? 'Edit User' : 'Add New User' }}</div>
            <button class="modal-close" wire:click="closeModal">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

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
                <input wire:model="email" class="form-input" type="email" placeholder="user@example.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Password</label>
                <input wire:model="password" class="form-input" type="password"
                    placeholder="{{ $isEditing ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
                @if($isEditing)
                    <div class="form-hint">Leave blank to keep the existing password.</div>
                @endif
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">Role</label>
                <select wire:model="role" class="form-select"
                    @if($isEditing && $editingId)
                        @php $editUser = \App\Models\User::find($editingId); @endphp
                        @if($editUser && $editUser->email === $defaultAdminEmail) disabled @endif
                    @endif
                >
                    <option value="user">● User — Standard access</option>
                    <option value="admin">★ Admin — Full access</option>
                </select>
                @error('role') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Profile Photo --}}
            <div class="form-group">
                <label class="form-label">Profile Photo <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--text-muted);">(optional)</span></label>
                <input wire:model="photo" class="form-input" type="file" accept="image/*"
                    style="padding:8px 14px;color:var(--text-muted);">
                @error('photo') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">JPG, PNG, or GIF — max 2MB</div>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn-secondary" wire:click="closeModal">Cancel</button>
            <button class="btn-primary" wire:click="save">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEditing ? 'Update User' : 'Create User' }}
            </button>
        </div>
    </div>
</div>
@endif


{{-- ═══════════════════════════════════════
     DELETE CONFIRMATION MODAL
═══════════════════════════════════════ --}}
@if ($showDeleteModal)
<div class="modal-backdrop" wire:click.self="closeDeleteModal">
    <div class="modal delete-modal">
        <div class="modal-body">
            <div class="delete-icon">
                <svg width="26" height="26" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div class="delete-title">Delete User?</div>
            <p class="delete-desc">
                You are about to permanently delete
                <span class="delete-name">{{ $deletingUserName }}</span>.
                This action cannot be undone.
            </p>
        </div>

        <div class="modal-footer" style="justify-content:center;gap:12px;padding-top:8px;">
            <button class="btn-secondary" wire:click="closeDeleteModal">Cancel</button>
            <button class="btn-danger" wire:click="confirmDelete">
                Yes, Delete
            </button>
        </div>
    </div>
</div>
@endif

</div>