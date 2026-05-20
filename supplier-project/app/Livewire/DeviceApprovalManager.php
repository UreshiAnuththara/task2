<?php

namespace App\Livewire;

use App\Models\DeviceAuthRequest;
use Livewire\Component;
use Livewire\WithPagination;

class DeviceApprovalManager extends Component
{
    use WithPagination;

    public string $tab    = 'pending';
    public string $search = '';

    // ── Confirmation Modal State ──
    public bool   $showConfirmModal = false;
    public ?int   $confirmId        = null;
    public string $confirmAction    = ''; // approve | reject | revoke | delete
    public string $confirmUserName  = '';
    public string $confirmDevice    = '';

    protected string $paginationTheme = 'tailwind';

    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingTab(): void    { $this->resetPage(); }

    // ── Open confirm modal ────────────────────────────────────────────────

    public function openConfirm(int $id, string $action): void
    {
        $req = DeviceAuthRequest::with('user')->findOrFail($id);

        $this->confirmId        = $id;
        $this->confirmAction    = $action;
        $this->confirmUserName  = $req->user?->name ?? 'Unknown';
        $this->confirmDevice    = $req->browserLabel() . ' · ' . $req->osLabel() . ' · ' . ($req->ip_address ?? '');
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal  = false;
        $this->confirmId         = null;
        $this->confirmAction     = '';
        $this->confirmUserName   = '';
        $this->confirmDevice     = '';
    }

    // ── Execute confirmed action ──────────────────────────────────────────

    public function executeConfirm(): void
    {
        if (! $this->confirmId) return;

        $req     = DeviceAuthRequest::findOrFail($this->confirmId);
        $adminId = auth()->id();
        $token   = $req->device_token;

        if ($this->confirmAction === 'approve') {

            // ── APPROVE ───────────────────────────────────────────────────
            // 1. Approve this record.
            // 2. Cascade approve ALL other records on the same device token
            //    + same user_agent (pending or rejected → approved).
            //    Every user on this device gets access immediately.

            $req->update([
                'status'       => 'approved',
                'responded_at' => now(),
                'approved_by'  => $adminId,
            ]);

            // Cascade by device_token
            DeviceAuthRequest::where('device_token', $token)
                ->where('id', '!=', $req->id)
                ->whereIn('status', ['pending', 'rejected'])
                ->update([
                    'status'       => 'approved',
                    'responded_at' => now(),
                    'approved_by'  => $adminId,
                ]);

            // Also cascade by user_agent (covers token drift cases)
            if ($req->user_agent) {
                DeviceAuthRequest::where('user_agent', $req->user_agent)
                    ->where('id', '!=', $req->id)
                    ->whereIn('status', ['pending', 'rejected'])
                    ->update([
                        'status'       => 'approved',
                        'responded_at' => now(),
                        'approved_by'  => $adminId,
                    ]);
            }

        } elseif (in_array($this->confirmAction, ['reject', 'revoke'])) {

            // ── REJECT / REVOKE ──────────────────────────────────────────
            // 1. Reject/revoke this record.
            // 2. Cascade reject ALL records sharing the same device_token
            //    OR the same user_agent (handles token drift edge cases).
            //    This ensures every user on this physical device is blocked
            //    even if their token drifted slightly.

            $req->update([
                'status'       => 'rejected',
                'responded_at' => now(),
                'approved_by'  => $adminId,
            ]);

            // Cascade by device_token (same token = same device)
            DeviceAuthRequest::where('device_token', $token)
                ->where('id', '!=', $req->id)
                ->whereIn('status', ['pending', 'approved'])
                ->update([
                    'status'       => 'rejected',
                    'responded_at' => now(),
                    'approved_by'  => $adminId,
                ]);

            // Also cascade by user_agent (covers token drift cases)
            if ($req->user_agent) {
                DeviceAuthRequest::where('user_agent', $req->user_agent)
                    ->where('id', '!=', $req->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->update([
                        'status'       => 'rejected',
                        'responded_at' => now(),
                        'approved_by'  => $adminId,
                    ]);
            }

        } elseif ($this->confirmAction === 'delete') {

            $req->delete();

        }

        $messages = [
            'approve' => "✓ Device approved for {$this->confirmUserName}. All users on this device are now approved.",
            'reject'  => "Device rejected for {$this->confirmUserName}. All users on this device have been blocked.",
            'revoke'  => "Device revoked for {$this->confirmUserName}. All users on this device have been blocked.",
            'delete'  => "Request deleted successfully.",
        ];

        session()->flash('success', $messages[$this->confirmAction] ?? 'Done.');

        $this->closeConfirmModal();
        $this->resetPage();
    }

    // ── Render ────────────────────────────────────────────────────────────

    public function render()
    {
        $requests = DeviceAuthRequest::with(['user', 'approvedBy'])
            ->when($this->tab !== 'all', fn($q) => $q->where('status', $this->tab))
            ->when($this->search, function ($q) {
                $s = '%' . $this->search . '%';
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', $s)->orWhere('email', 'like', $s)
                )->orWhere('ip_address', 'like', $s);
            })
            ->latest('requested_at')
            ->paginate(15);

        $counts = [
            'pending'  => DeviceAuthRequest::where('status', 'pending')->count(),
            'approved' => DeviceAuthRequest::where('status', 'approved')->count(),
            'rejected' => DeviceAuthRequest::where('status', 'rejected')->count(),
            'all'      => DeviceAuthRequest::count(),
        ];

        return view('livewire.device-approval-manager', [
            'requests' => $requests,
            'counts'   => $counts,
        ])->layout('layouts.app');
    }
}