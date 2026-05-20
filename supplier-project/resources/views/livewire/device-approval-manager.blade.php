<div>

@push('styles')
<style>
    /* ── Flash ── */
    .dam-flash { display:flex; align-items:center; gap:10px; padding:12px 18px; border-radius:10px; font-size:13px; font-weight:600; margin-bottom:20px; }
    .dam-flash-ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; }
    .dam-flash-err { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }

    /* ── Tabs ── */
    .dam-tabs { display:flex; gap:4px; border-bottom:2px solid #f1f5f9; margin-bottom:20px; }
    .dam-tab  { padding:9px 16px; border:none; border-bottom:2px solid transparent; background:none; cursor:pointer; font-size:13px; font-weight:600; color:#64748b; font-family:'Figtree',sans-serif; margin-bottom:-2px; display:flex; align-items:center; gap:6px; }
    .dam-tab.active { border-bottom-color:#2563eb; color:#2563eb; font-weight:700; }
    .dam-tab-count  { border-radius:99px; padding:1px 8px; font-size:11px; font-weight:700; }
    .dam-tab.active .dam-tab-count { background:#eff6ff; color:#2563eb; }
    .dam-tab:not(.active) .dam-tab-count { background:#f1f5f9; color:#94a3b8; }

    /* ── Table ── */
    .dam-card  { background:#fff; border-radius:14px; border:1px solid #e2e8f0; overflow:hidden; }
    .dam-table { width:100%; border-collapse:collapse; }
    .dam-table th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; background:#f8fafc; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
    .dam-table td { padding:13px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#374151; vertical-align:middle; }
    .dam-table tr:last-child td { border-bottom:none; }
    .dam-table tr:hover td { background:#fafbff; }

    /* ── Status badges ── */
    .badge-pending  { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#fef9c3; color:#854d0e; border-radius:99px; font-size:11px; font-weight:700; }
    .badge-approved { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#f0fdf4; color:#16a34a; border-radius:99px; font-size:11px; font-weight:700; }
    .badge-rejected { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#fef2f2; color:#dc2626; border-radius:99px; font-size:11px; font-weight:700; }
    .badge-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }

    /* ── Action buttons ── */
    .btn-approve  { padding:6px 13px; background:#22c55e; color:#fff; border:none; border-radius:6px; font-size:12px; font-weight:700; cursor:pointer; font-family:'Figtree',sans-serif; }
    .btn-approve:hover  { background:#16a34a; }
    .btn-reject   { padding:6px 13px; background:#ef4444; color:#fff; border:none; border-radius:6px; font-size:12px; font-weight:700; cursor:pointer; font-family:'Figtree',sans-serif; }
    .btn-reject:hover   { background:#dc2626; }
    .btn-ghost-sm { padding:6px 12px; background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; font-family:'Figtree',sans-serif; }
    .btn-ghost-sm:hover { background:#e2e8f0; }
    .btn-del      { padding:6px 10px; background:transparent; color:#cbd5e1; border:1px solid #e2e8f0; border-radius:6px; font-size:13px; cursor:pointer; }
    .btn-del:hover { color:#ef4444; border-color:#fecaca; background:#fef2f2; }

    /* ── Confirm Modal ── */
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,0.45); backdrop-filter:blur(4px); z-index:500; display:flex; align-items:center; justify-content:center; padding:20px; }
    .modal-box { background:#fff; border-radius:16px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.18); overflow:hidden; }
    .modal-header { padding:22px 24px 0; display:flex; align-items:flex-start; gap:14px; }
    .modal-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .modal-icon-approve { background:#f0fdf4; }
    .modal-icon-reject  { background:#fef2f2; }
    .modal-icon-delete  { background:#fff7ed; }
    .modal-title { font-size:17px; font-weight:800; color:#0f172a; margin-bottom:4px; }
    .modal-sub   { font-size:13px; color:#64748b; line-height:1.5; }
    .modal-device-info { margin:16px 24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; }
    .modal-device-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8; margin-bottom:6px; }
    .modal-device-name  { font-size:13px; font-weight:700; color:#0f172a; }
    .modal-device-meta  { font-size:12px; color:#64748b; margin-top:2px; }
    .modal-footer { padding:20px 24px 24px; display:flex; gap:10px; justify-content:flex-end; margin-top:20px; }
    .modal-btn-cancel  { padding:9px 18px; background:#f1f5f9; color:#374151; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Figtree',sans-serif; }
    .modal-btn-cancel:hover  { background:#e2e8f0; }
    .modal-btn-confirm-approve { padding:9px 20px; background:#22c55e; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; font-family:'Figtree',sans-serif; }
    .modal-btn-confirm-approve:hover { background:#16a34a; }
    .modal-btn-confirm-reject  { padding:9px 20px; background:#ef4444; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; font-family:'Figtree',sans-serif; }
    .modal-btn-confirm-reject:hover  { background:#dc2626; }
    .modal-btn-confirm-delete  { padding:9px 20px; background:#f97316; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; font-family:'Figtree',sans-serif; }
    .modal-btn-confirm-delete:hover  { background:#ea6c00; }

    @keyframes modal-in {
        from { opacity:0; transform:scale(0.95) translateY(8px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    .modal-box { animation: modal-in 0.18s ease; }
</style>
@endpush

<div style="max-width:1800px;margin:0 auto;">

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div class="dam-flash dam-flash-ok" x-data x-init="setTimeout(()=>$el.style.display='none',5000)">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="dam-flash dam-flash-err" x-data x-init="setTimeout(()=>$el.style.display='none',5000)">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin-bottom:3px;">Device Authorization</h2>
            <p style="font-size:13px;color:#64748b;">Review and approve device login requests from users.</p>
        </div>
        <div style="position:relative;">
            <svg width="15" height="15" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"
                style="position:absolute;left:11px;top:50%;transform:translateY(-50%);">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search user, IP…"
                style="padding:9px 14px 9px 34px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;font-family:'Figtree',sans-serif;color:#0f172a;background:#f8fafc;outline:none;width:230px;"
                onfocus="this.style.borderColor='#2563eb';this.style.background='#fff'"
                onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
        </div>
    </div>

    {{-- ── Tabs ── --}}
    <div class="dam-tabs">
        @foreach(['pending' => '⏳ Pending', 'approved' => '✅ Approved', 'rejected' => '❌ Rejected', 'all' => '📋 All'] as $key => $label)
            <button class="dam-tab {{ $tab === $key ? 'active' : '' }}" wire:click="$set('tab','{{ $key }}')">
                {{ $label }}
                <span class="dam-tab-count">{{ $counts[$key] }}</span>
            </button>
        @endforeach
    </div>

    {{-- ── Table ── --}}
    <div class="dam-card">
        @if($requests->isEmpty())
            <div style="padding:60px;text-align:center;">
                <svg width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;">
                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round"/>
                </svg>
                <div style="font-size:15px;font-weight:700;color:#94a3b8;">No requests found</div>
                <div style="font-size:13px;color:#cbd5e1;margin-top:4px;">
                    {{ $tab === 'pending' ? 'No pending device approvals.' : 'Nothing to show here.' }}
                </div>
            </div>
        @else
            <table class="dam-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Device Info</th>
                        <th>IP Address</th>
                        <th>Requested</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            {{-- User --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <img src="{{ $req->user?->profileImageUrl() }}" alt=""
                                        style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;">
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $req->user?->name ?? '—' }}</div>
                                        <div style="font-size:11px;color:#94a3b8;">{{ $req->user?->email ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Device --}}
                            <td>
                                <div style="font-size:13px;font-weight:600;color:#374151;">{{ $req->browserLabel() }}</div>
                                {{-- <div style="font-size:11px;color:#94a3b8;">{{ $req->osLabel() }}</div>
                                @if($req->fingerprint)
                                    @php $fp = $req->fingerprint; @endphp
                                    <div style="font-size:11px;color:#cbd5e1;margin-top:2px;">
                                        {{ $fp['screen'] ?? '' }}{{ isset($fp['timezone']) ? ' · '.$fp['timezone'] : '' }}
                                    </div> --}}
                                {{-- @endif --}}
                            </td>

                            {{-- IP --}}
                            <td>
                                <code style="font-size:12px;background:#f1f5f9;padding:3px 8px;border-radius:5px;color:#374151;">{{ $req->ip_address ?? '—' }}</code>
                            </td>

                            {{-- Time --}}
                            <td>
                                <div style="font-size:12px;font-weight:600;color:#374151;">{{ $req->requested_at?->format('d M Y') }}</div>
                                {{-- <div style="font-size:11px;color:#94a3b8;">{{ $req->requested_at?->format('h:i A') }}</div>
                                @if($req->responded_at)
                                    <div style="font-size:10px;color:#cbd5e1;margin-top:2px;">Actioned: {{ $req->responded_at->timezone('Asia/Colombo')->format('d M h:i A') }}</div>
                                @endif --}}
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge-pending"><span class="badge-dot" style="background:#eab308;"></span> Pending</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge-approved"><span class="badge-dot" style="background:#22c55e;"></span> Approved</span>
                                @else
                                    <span class="badge-rejected"><span class="badge-dot" style="background:#ef4444;"></span> Rejected</span>
                                @endif
                                @if($req->approvedBy)
                                    <div style="font-size:10px;color:#cbd5e1;margin-top:4px;">by {{ $req->approvedBy->name }}</div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                                    @if($req->status === 'pending')
                                        <button class="btn-approve" wire:click="openConfirm({{ $req->id }}, 'approve')">✓ Approve</button>
                                        <button class="btn-reject"  wire:click="openConfirm({{ $req->id }}, 'reject')">✗ Reject</button>
                                    @elseif($req->status === 'approved')
                                        <button class="btn-ghost-sm" wire:click="openConfirm({{ $req->id }}, 'revoke')">Revoke</button>
                                    @elseif($req->status === 'rejected')
                                        <button class="btn-approve" wire:click="openConfirm({{ $req->id }}, 'approve')">✓ Approve</button>
                                    @endif
                                    <button class="btn-del" wire:click="openConfirm({{ $req->id }}, 'delete')">🗑</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($requests->hasPages())
                <div style="padding:16px;border-top:1px solid #f1f5f9;">
                    {{ $requests->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

{{-- ══ Confirmation Modal ══ --}}
@if($showConfirmModal)
<div class="modal-backdrop" wire:click.self="closeConfirmModal">
    <div class="modal-box">

        {{-- Header --}}
        <div class="modal-header">
            {{-- Icon --}}
            <div class="modal-icon {{ $confirmAction === 'approve' ? 'modal-icon-approve' : ($confirmAction === 'delete' ? 'modal-icon-delete' : 'modal-icon-reject') }}">
                @if($confirmAction === 'approve')
                    <svg width="22" height="22" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                @elseif($confirmAction === 'delete')
                    <svg width="22" height="22" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                @else
                    <svg width="22" height="22" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                @endif
            </div>

            {{-- Title + subtitle --}}
            <div>
                @if($confirmAction === 'approve')
                    <div class="modal-title">Approve Device</div>
                    <div class="modal-sub">
                        Approve this device for <strong>{{ $confirmUserName }}</strong>?<br>
                        <span style="color:#16a34a;font-size:12px;">All users on this device will be auto-approved.</span>
                    </div>
                @elseif($confirmAction === 'reject')
                    <div class="modal-title">Reject Device</div>
                    <div class="modal-sub">
                        Reject this device for <strong>{{ $confirmUserName }}</strong>?<br>
                        <span style="color:#dc2626;font-size:12px;">All users on this device will be blocked.</span>
                    </div>
                @elseif($confirmAction === 'revoke')
                    <div class="modal-title">Revoke Device Access</div>
                    <div class="modal-sub">
                        Revoke access for <strong>{{ $confirmUserName }}</strong>?<br>
                        <span style="color:#dc2626;font-size:12px;">All users on this device will be blocked.</span>
                    </div>
                @elseif($confirmAction === 'delete')
                    <div class="modal-title">Delete Request</div>
                    <div class="modal-sub">
                        Permanently delete this device request?
                    </div>
                @endif
            </div>
        </div>

        {{-- Device info card --}}
        <div class="modal-device-info">
            <div class="modal-device-label">Device Details</div>
            <div class="modal-device-name">{{ $confirmDevice }}</div>
        </div>

        {{-- Footer buttons --}}
        <div class="modal-footer">
            <button class="modal-btn-cancel" wire:click="closeConfirmModal">Cancel</button>

            @if($confirmAction === 'approve')
                <button class="modal-btn-confirm-approve" wire:click="executeConfirm" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="executeConfirm">✓ Yes, Approve</span>
                    <span wire:loading wire:target="executeConfirm">Processing...</span>
                </button>
            @elseif($confirmAction === 'delete')
                <button class="modal-btn-confirm-delete" wire:click="executeConfirm" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="executeConfirm">🗑 Yes, Delete</span>
                    <span wire:loading wire:target="executeConfirm">Deleting...</span>
                </button>
            @else
                <button class="modal-btn-confirm-reject" wire:click="executeConfirm" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="executeConfirm">
                        {{ $confirmAction === 'revoke' ? 'Yes, Revoke' : 'Yes, Reject' }}
                    </span>
                    <span wire:loading wire:target="executeConfirm">Processing...</span>
                </button>
            @endif
        </div>

    </div>
</div>
@endif

</div>