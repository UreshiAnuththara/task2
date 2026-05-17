<?php

namespace App\Livewire;

use App\Models\User;
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
    public string $search   = '';
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $role     = 'user';
    public string $shift    = '';   // '' = no restriction, 'day', 'night'

    protected string $paginationTheme   = 'tailwind';
    protected string $defaultAdminEmail = 'ureshianuththara9@gmail.com';

    public array $roleSuggestions = ['admin', 'Production', 'HR', 'Accounting', 'Logistics', 'Sales', 'IT'];

    // ── Guard ──
    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->editingId,
            'password' => $pw,
            'role'     => 'required|string|max:50',
            'shift'    => 'nullable|in:,day,night',
        ];
    }

    protected array $messages = [
        'email.unique' => 'This email is already registered.',
        'password.min' => 'Password must be at least 6 characters.',
        'shift.in'     => 'Shift must be day or night.',
    ];

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Modal ──
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

        $this->editingId = $u->id;
        $this->name      = $u->name;
        $this->email     = $u->email;
        $this->role      = $u->role ?? 'user';
        $this->shift     = $u->shift ?? '';
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

        if ($this->isEditing) {
            $u = User::findOrFail($this->editingId);

            $role = $u->role;
            if ($this->isDefaultAdmin()) {
                $role = $this->role;
            } else {
                if ($u->role !== 'admin') {
                    $role = ($this->role === 'admin') ? $u->role : $this->role;
                }
            }

            // Admins don't get shift restrictions
            $shift = ($role === 'admin') ? null : ($this->shift ?: null);

            $data = [
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $role,
                'shift' => $shift,
            ];
            if ($this->password) $data['password'] = Hash::make($this->password);

            $u->update($data);
            session()->flash('success', 'User updated successfully.');
        } else {
            $role = $this->role;
            if (! $this->isDefaultAdmin() && $role === 'admin') {
                $role = 'user';
            }

            // Admins don't get shift restrictions
            $shift = ($role === 'admin') ? null : ($this->shift ?: null);

            User::create([
                'name'              => $this->name,
                'email'             => $this->email,
                'password'          => Hash::make($this->password),
                'role'              => $role,
                'shift'             => $shift,
                'email_verified_at' => now(),
            ]);
            session()->flash('success', 'User created successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

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
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%')
            )
            ->latest()->paginate(10);

        return view('livewire.user-manager', [
            'users'             => $users,
            'defaultAdminEmail' => $this->defaultAdminEmail,
            'isDefaultAdmin'    => $this->isDefaultAdmin(),
            'totalUsers'        => User::count(),
            'totalAdmins'       => User::where('role', 'admin')->count(),
            'totalOthers'       => User::where('role', '!=', 'admin')->count(),
        ])->layout('layouts.app');
    }

    private function resetForm(): void
    {
        $this->name = $this->email = $this->password = '';
        $this->role  = 'user';
        $this->shift = '';
        $this->editingId = null;
        $this->isEditing = false;
    }
}