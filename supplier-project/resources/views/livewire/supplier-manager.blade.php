<div>{{-- Livewire single root element --}}

@push('styles')
<style>
    :root {
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
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }

    .page-title { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.3px; }
    .page-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px;
        background: var(--accent); color: #fff;
        border: none; border-radius: 9px;
        font-size: 14px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; transition: background 0.15s, transform 0.1s;
    }
    .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }

    .btn-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px;
        background: var(--surface-2); color: var(--text);
        border: 1px solid var(--border); border-radius: 9px;
        font-size: 14px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; transition: background 0.15s;
    }
    .btn-secondary:hover { background: #243050; }

    /* ── Flash ── */
    .flash-success {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px;
        background: rgba(34,197,94,0.1);
        border: 1px solid rgba(34,197,94,0.25);
        border-radius: 10px; color: #86efac;
        font-size: 13px; font-weight: 600; margin-bottom: 20px;
    }

    /* ── Stats row ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px; margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 12px; padding: 18px 20px;
    }

    .stat-card .sc-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 6px; }
    .stat-card .sc-value { font-size: 28px; font-weight: 800; color: var(--text); letter-spacing: -1px; }

    /* ── Search ── */
    .search-wrap { position: relative; max-width: 320px; margin-bottom: 20px; }
    .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted); }

    .search-input {
        width: 100%; padding: 10px 14px 10px 38px;
        background: var(--surface); border: 1.5px solid var(--border);
        border-radius: 9px; color: var(--text); font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none; transition: border-color 0.2s;
    }
    .search-input:focus { border-color: var(--accent); }
    .search-input::placeholder { color: var(--text-muted); }

    /* ── Table Card ── */
    .table-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; overflow: hidden;
    }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead { background: var(--surface-2); }
    .data-table thead th {
        padding: 13px 16px; text-align: left;
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; color: var(--text-muted);
        border-bottom: 1px solid var(--border);
    }

    .data-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.02); }

    .data-table td { padding: 14px 16px; font-size: 14px; color: var(--text); vertical-align: middle; }

    /* Supplier avatar in table */
    .supplier-init {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--accent-soft); color: #60a5fa;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 800; flex-shrink: 0;
        border: 1.5px solid rgba(37,99,235,0.2);
    }

    .supplier-name { font-weight: 700; color: var(--text); }
    .supplier-email { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

    /* Action Buttons */
    .action-btns { display: flex; gap: 6px; justify-content: flex-end; }

    .btn-action {
        padding: 6px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; border: 1px solid transparent;
        transition: all 0.15s;
        display: inline-flex; align-items: center; gap: 4px;
    }

    .btn-edit { background: rgba(37,99,235,0.1); color: #60a5fa; border-color: rgba(37,99,235,0.2); }
    .btn-edit:hover { background: rgba(37,99,235,0.2); }

    .btn-delete { background: rgba(239,68,68,0.08); color: #f87171; border-color: rgba(239,68,68,0.2); }
    .btn-delete:hover { background: rgba(239,68,68,0.15); }

    /* ── Modal ── */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);
        z-index: 200; display: flex; align-items: center; justify-content: center;
        padding: 20px;
    }

    .modal {
        background: #111827; border: 1px solid var(--border);
        border-radius: 16px; width: 100%; max-width: 500px;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0,0,0,0.6);
    }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 22px 24px 16px; border-bottom: 1px solid var(--border);
    }

    .modal-title { font-size: 18px; font-weight: 800; color: var(--text); }

    .modal-close {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--surface-2); border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); transition: background 0.15s;
    }
    .modal-close:hover { background: #243050; color: var(--text); }

    .modal-body { padding: 22px 24px; }

    .form-group { margin-bottom: 18px; }

    .form-label {
        display: block; font-size: 12px; font-weight: 700;
        color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px;
        margin-bottom: 7px;
    }

    .form-input, .form-textarea {
        width: 100%; padding: 12px 14px;
        background: var(--surface-2); border: 1.5px solid var(--border);
        border-radius: 9px; color: var(--text); font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-input:focus, .form-textarea:focus {
        border-color: var(--accent); box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    .form-textarea { resize: vertical; min-height: 80px; }
    .form-error { font-size: 12px; color: #f87171; margin-top: 5px; }

    .modal-footer {
        display: flex; justify-content: flex-end; gap: 10px;
        padding: 16px 24px 22px; border-top: 1px solid var(--border);
    }

    /* ── Delete Modal ── */
    .delete-icon {
        width: 56px; height: 56px; border-radius: 50%;
        background: rgba(239,68,68,0.12); border: 2px solid rgba(239,68,68,0.2);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
    }

    .delete-modal .modal-body { text-align: center; }
    .delete-title { font-size: 18px; font-weight: 800; color: var(--text); margin-bottom: 8px; }
    .delete-desc { font-size: 14px; color: var(--text-muted); line-height: 1.6; }
    .delete-name { font-weight: 700; color: #f87171; }

    .btn-danger {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px; background: var(--danger); color: #fff;
        border: none; border-radius: 9px;
        font-size: 14px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; transition: background 0.15s;
    }
    .btn-danger:hover { background: #dc2626; }

    /* ── Pagination ── */
    .pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--border); }

    /* ── Empty ── */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
    .empty-state svg { width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3; display: block; }
    .empty-state p { font-size: 15px; font-weight: 600; }

    /* ════════════════ PRINT STYLES ════════════════ */
    #printOverlay { display: none; }
    .row-num-badge { display: inline; }

    @media print {
        #crudDashboard       { display: none !important; }
        #printOverlay        { display: block !important; }

        @page { size: A4 portrait; margin: 0; }

        .print-page {
            page-break-after: always; break-after: page;
            width: 210mm !important; height: 297mm !important;
            padding: 14mm 15mm 0 !important; margin: 0 auto !important;
            box-shadow: none !important; box-sizing: border-box !important;
            display: flex !important; flex-direction: column !important;
            background: #fff; overflow: hidden;
        }
        .print-page:last-child { page-break-after: avoid; break-after: auto; }
        .print-page-body { flex: 1; overflow: hidden; }
        .print-footer {
            flex-shrink: 0 !important; display: flex !important;
            justify-content: space-between !important; align-items: center !important;
            border-top: 1px solid #d1d5db !important; padding: 5px 0 12mm !important;
            margin-top: auto !important; font-size: 8pt !important;
            font-family: 'Segoe UI', Arial, sans-serif !important;
            color: #9ca3af !important; background: #fff !important;
        }
        .supplier-card { page-break-inside: avoid; break-inside: avoid; }
        .row-num-badge { display: none !important; }
        #print-pages-wrapper { background: none !important; padding: 0 !important; }
    }
</style>
@endpush


{{-- ════════════════════════════════════════════════════════════════
     SECTION A — CRUD DASHBOARD
════════════════════════════════════════════════════════════════ --}}
<div id="crudDashboard">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <div class="page-title">Suppliers</div>
            <div class="page-subtitle">Manage your supplier directory</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button wire:click="openPrint" class="btn-secondary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print List
            </button>
            <button wire:click="openCreateModal" class="btn-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Supplier
            </button>
        </div>
    </div>

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="flash-success">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="sc-label">Total Suppliers</div>
            <div class="sc-value">{{ \App\Models\Supplier::count() }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">This Month</div>
            <div class="sc-value">{{ \App\Models\Supplier::whereMonth('created_at', now()->month)->count() }}</div>
        </div>
    </div>

    {{-- Search --}}
    <div class="search-wrap">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input wire:model.live.debounce.300ms="search"
               class="search-input" type="text"
               placeholder="Search by name or email…">
    </div>

    {{-- Table --}}
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Added</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="supplier-init">{{ strtoupper(substr($supplier->name, 0, 1)) }}</div>
                            <div>
                                <div class="supplier-name">{{ $supplier->name }}</div>
                                <div class="supplier-email">{{ $supplier->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $supplier->phone }}</td>
                    <td style="color:var(--text-muted);font-size:13px;max-width:200px;">
                        <span style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;">
                            {{ $supplier->address }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $supplier->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <button wire:click="openEditModal({{ $supplier->id }})" class="btn-action btn-edit">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <button wire:click="openDeleteModal({{ $supplier->id }})" class="btn-action btn-delete">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p>No suppliers found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if ($suppliers->hasPages())
            <div class="pagination-wrap">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>{{-- end crudDashboard --}}


{{-- ═══════════════════════════════════════
     CREATE / EDIT MODAL
═══════════════════════════════════════ --}}
@if ($showModal)
<div class="modal-backdrop" wire:click.self="closeModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">{{ $isEditing ? 'Edit Supplier' : 'Add New Supplier' }}</div>
            <button class="modal-close" wire:click="closeModal">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Company / Supplier Name</label>
                <input wire:model="name" class="form-input" type="text" placeholder="e.g. ABC Suppliers Ltd.">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input wire:model="email" class="form-input" type="email" placeholder="contact@supplier.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input wire:model="phone" class="form-input" type="text" placeholder="+94 71 234 5678">
                @error('phone') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea wire:model="address" class="form-textarea" placeholder="Full business address"></textarea>
                @error('address') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" wire:click="closeModal">Cancel</button>
            <button class="btn-primary" wire:click="save">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEditing ? 'Update Supplier' : 'Create Supplier' }}
            </button>
        </div>
    </div>
</div>
@endif


{{-- ═══════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════ --}}
@if ($showDeleteModal)
<div class="modal-backdrop" wire:click.self="closeDeleteModal">
    <div class="modal" style="max-width:420px;">
        <div class="modal-body">
            <div class="delete-icon">
                <svg width="26" height="26" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div class="delete-title">Delete Supplier?</div>
            <p class="delete-desc">
                You are about to permanently delete
                <span class="delete-name">{{ $deletingSupplierName }}</span>.
                This action cannot be undone.
            </p>
        </div>
        <div class="modal-footer" style="justify-content:center;gap:12px;padding-top:8px;">
            <button class="btn-secondary" wire:click="closeDeleteModal">Cancel</button>
            <button class="btn-danger" wire:click="confirmDelete">Yes, Delete</button>
        </div>
    </div>
</div>
@endif


{{-- ════════════════════════════════════════════════════════════════
     SECTION B — PRINT OVERLAY
════════════════════════════════════════════════════════════════ --}}
<div id="printOverlay">

    @if ($showPrint)

        {{-- Print Toolbar (screen only) --}}
        <div id="printToolbar"
             style="position:fixed;top:0;left:0;right:0;height:56px;background:#1e2d45;
                    display:flex;align-items:center;justify-content:space-between;
                    padding:0 24px;z-index:300;border-bottom:1px solid #2d3f5e;">
            <span style="font-size:14px;font-weight:700;color:#f1f5f9;">Print Preview — Supplier Directory</span>
            <div style="display:flex;gap:10px;">
                <button wire:click="closePrint"
                    style="padding:8px 16px;background:var(--surface-2);color:#94a3b8;border:1px solid #1e2d45;
                           border-radius:8px;font-size:13px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;">
                    ✕ Close
                </button>
                <button onclick="window.print()"
                    style="padding:8px 16px;background:#2563eb;color:#fff;border:none;
                           border-radius:8px;font-size:13px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;">
                    🖨️ Print
                </button>
            </div>
        </div>

        {{-- Scrollable Page Area --}}
        <div id="print-pages-wrapper"
             style="padding-top:64px;background:#374151;min-height:100vh;">

            @php
                $chunks     = $allSuppliers->chunk(6);
                $totalPages = $chunks->count() ?: 1;
                $rowNum     = 1;
            @endphp

            @forelse ($chunks as $pageIdx => $chunk)
            <div class="print-page"
                 style="width:210mm;min-height:277mm;padding:14mm 15mm 0;background:#fff;
                        margin:0 auto 28px;box-shadow:0 4px 24px rgba(0,0,0,.2);
                        box-sizing:border-box;font-family:'Segoe UI',Arial,sans-serif;
                        display:flex;flex-direction:column;">

                <div class="print-page-body" style="flex:1;">

                    @if ($pageIdx === 0)
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;
                                border-bottom:3px solid #1e40af;padding-bottom:12px;margin-bottom:20px;">
                        <div>
                            <div style="font-size:20pt;font-weight:800;color:#1e3a8a;">Supplier Directory</div>
                            <div style="font-size:9pt;color:#6b7280;margin-top:4px;">
                                {{ $allSuppliers->count() }} {{ Str::plural('record', $allSuppliers->count()) }}
                                &nbsp;·&nbsp; Sorted alphabetically
                            </div>
                        </div>
                        <div style="text-align:right;font-size:9pt;color:#6b7280;line-height:1.9;">
                            <div><strong style="color:#374151;">Printed by:</strong>&nbsp; {{ $printedBy }}</div>
                        </div>
                    </div>
                    @else
                    <div style="display:flex;justify-content:space-between;align-items:center;
                                border-bottom:2px solid #e5e7eb;padding-bottom:8px;margin-bottom:16px;">
                        <div style="font-size:13pt;font-weight:700;color:#1e3a8a;">
                            Supplier Directory
                            <span style="font-size:10pt;font-weight:400;color:#9ca3af;">(continued)</span>
                        </div>
                    </div>
                    @endif

                    <div style="display:flex;flex-direction:column;gap:10px;">
                        @foreach ($chunk as $supplier)
                        <div class="supplier-card"
                             style="display:flex;align-items:flex-start;gap:14px;padding:12px 14px;
                                    border:1px solid #e5e7eb;border-left:4px solid #1d4ed8;
                                    border-radius:8px;background:#f9fafb;">

                            <div style="width:44px;height:44px;border-radius:50%;background:#dbeafe;
                                        flex-shrink:0;display:flex;align-items:center;justify-content:center;
                                        font-size:16pt;font-weight:800;color:#1d4ed8;">
                                {{ strtoupper(substr($supplier->name, 0, 1)) }}
                            </div>

                            <div style="flex:1;min-width:0;">
                                <div style="font-size:12pt;font-weight:700;color:#111827;margin-bottom:6px;">
                                    {{ $supplier->name }}
                                    <span class="row-num-badge" style="font-size:8pt;color:#d1d5db;font-weight:400;margin-left:8px;">
                                        #{{ $rowNum++ }}
                                    </span>
                                </div>
                                <div style="display:flex;gap:28px;flex-wrap:wrap;margin-bottom:5px;">
                                    <div style="display:flex;align-items:center;gap:5px;font-size:9.5pt;color:#374151;">
                                        <span style="color:#6b7280;">✉</span> {{ $supplier->email }}
                                    </div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:9.5pt;color:#374151;">
                                        <span style="color:#6b7280;">☎</span> {{ $supplier->phone }}
                                    </div>
                                </div>
                                <div style="display:flex;align-items:flex-start;gap:5px;font-size:9pt;color:#6b7280;">
                                    <span style="flex-shrink:0;margin-top:1px;">📍</span>
                                    <span>{{ $supplier->address }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>

                <div class="print-footer"
                     style="display:flex;justify-content:space-between;align-items:center;
                            border-top:1px solid #d1d5db;padding:5px 0 12mm;margin-top:auto;
                            font-size:8pt;color:#9ca3af;background:#fff;">
                    <span>Page {{ $pageIdx + 1 }} of {{ $totalPages }}</span>
                    <span>{{ $printedBy }}</span>
                </div>

            </div>
            @empty
            <div style="width:210mm;padding:20mm;background:#fff;margin:0 auto;
                        font-family:sans-serif;color:#9ca3af;font-size:13pt;box-sizing:border-box;">
                No suppliers to print.
            </div>
            @endforelse

        </div>

    @endif
</div>


{{-- ════════ JS ════════ --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('open-print-dialog', () => {
            setTimeout(() => { window.print(); }, 300);
        });
    });
</script>

</div>