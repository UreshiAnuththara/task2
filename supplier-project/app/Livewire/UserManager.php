<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    use WithPagination;

    // ── UI State ──
    public bool   $showModal       = false;
    public bool   $isEditing       = false;
    public ?int   $editingId       = null;
    public bool   $showDeleteModal = false;
    public ?int   $deletingId      = null;
    public string $deletingName    = '';

    // ── Role Manager Modal ──
    public bool   $showRoleModal  = false;
    public string $newRoleName    = '';
    public string $newRoleDesc    = '';
    public ?int   $editingRoleId  = null;
    public bool   $isEditingRole  = false;

    // ── Form Fields ──
    public string $search    = '';
    public string $name      = '';
    public string $email     = '';
    public string $password  = '';
    public string $role      = 'user';

    // ── Shift Fields ──
    // shiftEnabled: false = 24 hours (no restriction), true = custom time range
    public bool   $shiftEnabled = false;
    public string $shiftStart   = '08:00';
    public string $shiftEnd     = '18:00';

    // Track if currently editing an admin user (to lock role field)
    public bool $editingIsAdmin = false;

    protected string $paginationTheme   = 'tailwind';
    protected string $defaultAdminEmail = 'ureshianuththara9@gmail.com';

    // ── Mount ──
    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $this->shiftEnabled = false;
    }

    // ── Helpers ──
    public function isDefaultAdmin(): bool
    {
        return auth()->user()->email === $this->defaultAdminEmail;
    }

    // ── Validation ──
    protected function rules(): array
    {
        $pw = $this->isEditing ? 'nullable|string|min:6' : 'required|string|min:6';
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $this->editingId,
            'password'   => $pw,
            'role'       => 'required|string|max:50',
            'shiftStart' => 'nullable|date_format:H:i',
            'shiftEnd'   => 'nullable|date_format:H:i',
        ];
    }

    protected array $messages = [
        'email.unique'           => 'This email is already registered.',
        'password.min'           => 'Password must be at least 6 characters.',
        'shiftStart.date_format' => 'Shift start must be in HH:MM format.',
        'shiftEnd.date_format'   => 'Shift end must be in HH:MM format.',
    ];

    public function updatingSearch(): void { $this->resetPage(); }

    // ── User Modal ──
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $u = User::findOrFail($id);

        if ($u->email === $this->defaultAdminEmail) {
            session()->flash('error', 'The default administrator can only edit their own profile via Profile Settings.');
            return;
        }

        if ($u->role === 'admin' && !$this->isDefaultAdmin()) {
            session()->flash('error', 'Only the default administrator can edit other admin accounts.');
            return;
        }

        $this->editingId      = $u->id;
        $this->name           = $u->name;
        $this->email          = $u->email;
        $this->role           = $u->role ?? 'user';
        $this->editingIsAdmin = ($u->role === 'admin');

        // Load shift — support both old and new column formats
        $hasShift = !empty($u->shift_type) || !empty($u->shift);
        if ($hasShift && ($u->shift_type !== null || $u->shift !== null)) {
            $shiftVal = $u->shift_type ?? $u->shift;
            if (in_array($shiftVal, ['day', 'night', 'custom'])) {
                $this->shiftEnabled = true;
                $this->shiftStart   = $u->shift_start ?? ($shiftVal === 'night' ? '18:00' : '08:00');
                $this->shiftEnd     = $u->shift_end   ?? ($shiftVal === 'night' ? '08:00' : '18:00');
            } else {
                $this->shiftEnabled = false;
                $this->shiftStart   = '08:00';
                $this->shiftEnd     = '18:00';
            }
        } else {
            $this->shiftEnabled = false;
            $this->shiftStart   = '08:00';
            $this->shiftEnd     = '18:00';
        }

        $this->password  = '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        // Validate shift times if shift is enabled
        if ($this->shiftEnabled && $this->shiftStart === $this->shiftEnd) {
            $this->addError('shiftEnd', 'Start time and end time cannot be the same.');
            return;
        }

        $role = $this->role;
        if (!$this->isDefaultAdmin() && $role === 'admin') {
            $role = 'user';
        }

        // Build shift data
        if ($role === 'admin') {
            // Admins always unrestricted
            $shiftType = $shiftStart = $shiftEnd = $shiftLegacy = null;
        } elseif ($this->shiftEnabled) {
            // Determine day/night from time direction
            $autoType    = $this->shiftStart < $this->shiftEnd ? 'day' : 'night';
            $shiftType   = $autoType;
            $shiftStart  = $this->shiftStart;
            $shiftEnd    = $this->shiftEnd;
            $shiftLegacy = $autoType;
        } else {
            // 24 hours — no restriction
            $shiftType = $shiftStart = $shiftEnd = $shiftLegacy = null;
        }

        if ($this->isEditing) {
            $u    = User::findOrFail($this->editingId);
            $role = $u->role; // Preserve original role on edit

            $data = [
                'name'        => $this->name,
                'email'       => $this->email,
                'role'        => $role,
                'shift_type'  => $shiftType,
                'shift_start' => $shiftStart,
                'shift_end'   => $shiftEnd,
                'shift'       => $shiftLegacy,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $u->update($data);
            session()->flash('success', 'User updated successfully.');
        } else {
            User::create([
                'name'              => $this->name,
                'email'             => $this->email,
                'password'          => Hash::make($this->password),
                'role'              => $role,
                'shift_type'        => $shiftType,
                'shift_start'       => $shiftStart,
                'shift_end'         => $shiftEnd,
                'shift'             => $shiftLegacy,
                'email_verified_at' => now(),
            ]);
            session()->flash('success', 'User created successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    // ── Delete ──
    public function openDeleteModal(int $id): void
    {
        $u = User::findOrFail($id);
        if ($u->email === $this->defaultAdminEmail) {
            session()->flash('error', 'The default administrator cannot be deleted.');
            return;
        }
        $this->deletingId      = $u->id;
        $this->deletingName    = $u->name;
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
            $u = User::findOrFail($this->deletingId);
            if ($u->email !== $this->defaultAdminEmail) {
                $u->delete();
                session()->flash('success', 'User deleted.');
            }
            $this->closeDeleteModal();
            $this->resetPage();
        }
    }

    // ── Role Manager ──
    public function openRoleModal(): void
    {
        $this->newRoleName = $this->newRoleDesc = '';
        $this->editingRoleId = null;
        $this->isEditingRole = false;
        $this->showRoleModal = true;
    }

    public function openEditRoleModal(int $id): void
    {
        $r = UserRole::findOrFail($id);
        if ($r->is_system) {
            session()->flash('error', 'System roles cannot be edited.');
            return;
        }
        $this->editingRoleId = $r->id;
        $this->newRoleName   = $r->name;
        $this->newRoleDesc   = $r->description ?? '';
        $this->isEditingRole = true;
        $this->showRoleModal = true;
    }

    public function closeRoleModal(): void
    {
        $this->showRoleModal = false;
        $this->newRoleName = $this->newRoleDesc = '';
        $this->editingRoleId = null;
        $this->isEditingRole = false;
        $this->resetValidation();
    }

    public function saveRole(): void
    {
        $this->validate([
            'newRoleName' => 'required|string|max:50|unique:user_roles,name,' . ($this->editingRoleId ?? 'NULL'),
            'newRoleDesc' => 'nullable|string|max:255',
        ], [
            'newRoleName.required' => 'Role name is required.',
            'newRoleName.unique'   => 'This role name already exists.',
        ]);

        if ($this->isEditingRole && $this->editingRoleId) {
            UserRole::findOrFail($this->editingRoleId)->update([
                'name'        => $this->newRoleName,
                'description' => $this->newRoleDesc ?: null,
            ]);
            session()->flash('success', 'Role updated.');
        } else {
            UserRole::create([
                'name'        => $this->newRoleName,
                'description' => $this->newRoleDesc ?: null,
                'is_system'   => false,
            ]);
            session()->flash('success', 'Role "' . $this->newRoleName . '" created.');
        }
        $this->closeRoleModal();
    }

    public function deleteRole(int $id): void
    {
        $r = UserRole::findOrFail($id);
        if ($r->is_system) {
            session()->flash('error', 'System roles cannot be deleted.');
            return;
        }
        $r->delete();
        session()->flash('success', 'Role deleted.');
    }

    // ── Render ──
    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->latest()->paginate(10);

        $roles = UserRole::orderBy('is_system', 'desc')->orderBy('name')->get();

        // Shift preview for live feedback in modal
        $previewIsDay  = $this->shiftEnabled
            ? ($this->shiftStart < $this->shiftEnd)
            : null;
        $previewSame   = $this->shiftEnabled
            ? ($this->shiftStart === $this->shiftEnd)
            : false;

        return view('livewire.user-manager', [
            'users'             => $users,
            'roles'             => $roles,
            'defaultAdminEmail' => $this->defaultAdminEmail,
            'isDefaultAdmin'    => $this->isDefaultAdmin(),
            'totalUsers'        => User::count(),
            'totalAdmins'       => User::where('role', 'admin')->count(),
            'totalOthers'       => User::where('role', '!=', 'admin')->count(),
            'previewIsDay'      => $previewIsDay,
            'previewSame'       => $previewSame,
        ])->layout('layouts.app');
    }

    private function resetForm(): void
    {
        $this->name         = $this->email = $this->password = '';
        $this->role         = 'user';
        $this->shiftEnabled = false;
        $this->shiftStart   = '08:00';
        $this->shiftEnd     = '18:00';
        $this->editingId    = null;
        $this->isEditing    = false;
        $this->editingIsAdmin = false;
    }
}