<?php

namespace App\Livewire;

use App\Models\UserRole;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class RoleManager extends Component
{
    use WithPagination;

    // ── Modal State ──
    public bool   $showModal    = false;
    public bool   $isEditing    = false;
    public ?int   $editingId    = null;

    // ── Search ──
    public string $search       = '';

    // ── Delete Modal ──
    public bool   $showDeleteModal = false;
    public ?int   $deletingId      = null;
    public string $deletingName    = '';

    // ── Form Fields ──
    public string $name         = '';
    public string $description  = '';
    public bool   $hasTimeLimit = false;
    public string $loginStart   = '08:00';
    public string $loginEnd     = '18:00';

    protected string $paginationTheme = 'tailwind';

    // ── Mount ──
    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    // ── Computed preview for live feedback ──
    public function getPreviewIsOvernightProperty(): bool
    {
        return $this->hasTimeLimit && $this->loginStart >= $this->loginEnd;
    }

    public function getPreviewIsSameProperty(): bool
    {
        return $this->hasTimeLimit && $this->loginStart === $this->loginEnd;
    }

    // ── Validation ──
    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:50|unique:user_roles,name,' . ($this->editingId ?? 'NULL'),
            'description' => 'nullable|string|max:255',
            'loginStart'  => 'nullable|date_format:H:i',
            'loginEnd'    => 'nullable|date_format:H:i',
        ];
    }

    protected array $messages = [
        'name.required'      => 'Role name is required.',
        'name.unique'        => 'This role name already exists.',
        'loginStart.date_format' => 'Start time must be in HH:MM format.',
        'loginEnd.date_format'   => 'End time must be in HH:MM format.',
    ];

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Open Create Modal ──
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    // ── Open Edit Modal ──
    public function openEditModal(int $id): void
    {
        $r = UserRole::findOrFail($id);

        if ($r->is_system) {
            session()->flash('error', 'System roles cannot be edited.');
            return;
        }

        $this->editingId    = $r->id;
        $this->name         = $r->name;
        $this->description  = $r->description ?? '';
        $this->hasTimeLimit = $r->hasLoginRestriction();
        $this->loginStart   = $r->login_start ?? '08:00';
        $this->loginEnd     = $r->login_end   ?? '18:00';
        $this->isEditing    = true;
        $this->showModal    = true;
    }

    // ── Close Modal ──
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    // ── Save (Create / Update) ──
    public function save(): void
    {
        $this->validate();

        if ($this->hasTimeLimit && $this->loginStart === $this->loginEnd) {
            $this->addError('loginEnd', 'Start and end time cannot be the same.');
            return;
        }

        $data = [
            'name'        => $this->name,
            'description' => $this->description ?: null,
            'login_start' => $this->hasTimeLimit ? $this->loginStart : null,
            'login_end'   => $this->hasTimeLimit ? $this->loginEnd   : null,
        ];

        if ($this->isEditing) {
            UserRole::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Role "' . $this->name . '" updated successfully.');
        } else {
            $data['is_system'] = false;
            UserRole::create($data);
            session()->flash('success', 'Role "' . $this->name . '" created successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    // ── Delete ──
    public function openDeleteModal(int $id): void
    {
        $r = UserRole::findOrFail($id);
        if ($r->is_system) {
            session()->flash('error', 'System roles cannot be deleted.');
            return;
        }
        $this->deletingId   = $r->id;
        $this->deletingName = $r->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->deletingName    = '';
    }

    public function confirmDelete(): void
    {
        if ($this->deletingId) {
            $r = UserRole::findOrFail($this->deletingId);
            if (!$r->is_system) {
                // Count users still on this role
                $count = User::where('role', $r->name)->count();
                if ($count > 0) {
                    session()->flash('error', "Cannot delete \"{$r->name}\" — {$count} user(s) still assigned to this role.");
                    $this->closeDeleteModal();
                    return;
                }
                $r->delete();
                session()->flash('success', 'Role deleted.');
            }
            $this->closeDeleteModal();
            $this->resetPage();
        }
    }

    // ── Render ──
    public function render()
    {
        $roles = UserRole::orderBy('is_system', 'desc')
                         ->orderBy('name')
                         ->when($this->search, fn($q) =>
                             $q->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('description', 'like', '%' . $this->search . '%')
                         )
                         ->paginate(10);

        // For each role, count assigned users
        $userCounts = User::whereIn('role', UserRole::pluck('name'))
                          ->selectRaw('role, count(*) as cnt')
                          ->groupBy('role')
                          ->pluck('cnt', 'role');

        $previewIsOvernight = $this->hasTimeLimit && $this->loginStart >= $this->loginEnd;
        $previewIsSame      = $this->hasTimeLimit && $this->loginStart === $this->loginEnd;

        return view('livewire.role-manager', [
            'roles'             => $roles,
            'userCounts'        => $userCounts,
            'previewIsOvernight'=> $previewIsOvernight,
            'previewIsSame'     => $previewIsSame,
        ])->layout('layouts.app');
    }

    // ── Helpers ──
    private function resetForm(): void
    {
        $this->name         = '';
        $this->description  = '';
        $this->hasTimeLimit = false;
        $this->loginStart   = '08:00';
        $this->loginEnd     = '18:00';
        $this->editingId    = null;
        $this->isEditing    = false;
    }
}