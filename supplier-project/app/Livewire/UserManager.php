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

    // ── Form Fields ──
    public string $search    = '';
    public string $name      = '';
    public string $email     = '';
    public string $password  = '';
    public string $role      = 'user';

    // Track if currently editing an admin user (to lock role field)
    public bool $editingIsAdmin = false;

    protected string $paginationTheme   = 'tailwind';
    protected string $defaultAdminEmail = 'ureshianuththara9@gmail.com';

    // ── Mount ──
    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    // ── Helpers ──
    public function isDefaultAdmin(): bool
    {
        return auth()->user()->email === $this->defaultAdminEmail;
    }

    /**
     * Get the UserRole model for the currently selected role in the form.
     * Used to show login time preview when admin selects a role.
     */
    public function getSelectedRoleModelProperty(): ?UserRole
    {
        if (!$this->role || $this->role === 'admin') return null;
        return UserRole::where('name', $this->role)->first();
    }

    // ── Validation ──
    protected function rules(): array
    {
        $pw = $this->isEditing ? 'nullable|string|min:6' : 'required|string|min:6';
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->editingId,
            'password' => $pw,
            'role'     => 'required|string|max:50',
        ];
    }

    protected array $messages = [
        'email.unique'  => 'This email is already registered.',
        'password.min'  => 'Password must be at least 6 characters.',
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
        $this->password       = '';
        $this->isEditing      = true;
        $this->showModal      = true;
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

        $role = $this->role;

        // Non-default-admin cannot assign admin role
        if (!$this->isDefaultAdmin() && $role === 'admin') {
            $role = 'user';
        }

        if ($this->isEditing) {
            $u = User::findOrFail($this->editingId);

            // Non-default-admin editing an existing admin — preserve their role
            if ($u->role === 'admin' && !$this->isDefaultAdmin()) {
                $role = 'admin';
            }

            $data = [
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $role,
                // Clear legacy shift columns — restriction is now role-based
                'shift_type'  => null,
                'shift_start' => null,
                'shift_end'   => null,
                'shift'       => null,
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
                'shift_type'        => null,
                'shift_start'       => null,
                'shift_end'         => null,
                'shift'             => null,
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

        // Selected role model for live preview in the modal
        $selectedRoleModel = ($this->role && $this->role !== 'admin')
            ? $roles->firstWhere('name', $this->role)
            : null;

        return view('livewire.user-manager', [
            'users'             => $users,
            'roles'             => $roles,
            'selectedRoleModel' => $selectedRoleModel,
            'defaultAdminEmail' => $this->defaultAdminEmail,
            'isDefaultAdmin'    => $this->isDefaultAdmin(),
            'totalUsers'        => User::count(),
            'totalAdmins'       => User::where('role', 'admin')->count(),
            'totalOthers'       => User::where('role', '!=', 'admin')->count(),
        ])->layout('layouts.app');
    }

    private function resetForm(): void
    {
        $this->name           = $this->email = $this->password = '';
        $this->role           = 'user';
        $this->editingId      = null;
        $this->isEditing      = false;
        $this->editingIsAdmin = false;
    }
}