<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    use WithPagination, WithFileUploads;

    // ── UI State ──────────────────────────────────────────────────────────────
    public bool   $showModal        = false;
    public bool   $isEditing        = false;
    public ?int   $editingId        = null;
    public bool   $showDeleteModal  = false;
    public ?int   $deletingId       = null;
    public string $deletingUserName = '';

    // ── Form Fields ───────────────────────────────────────────────────────────
    public string $search   = '';
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $role     = 'user';
    public $photo; // uploaded file

    protected string $paginationTheme = 'tailwind';

    // Default admin email — this user is protected from edit/delete
    protected string $defaultAdminEmail = 'ureshianuththara9@gmail.com';

    // ── Validation ────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        $passwordRule = $this->isEditing ? 'nullable|string|min:8' : 'required|string|min:8';

        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $this->editingId,
            'password' => $passwordRule,
            'role'     => 'required|in:admin,user',
            'photo'    => 'nullable|image|max:2048',
        ];
    }

    protected array $messages = [
        'email.unique'    => 'This email is already registered.',
        'password.min'    => 'Password must be at least 8 characters.',
    ];

    // ── Lifecycle ─────────────────────────────────────────────────────────────
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Guard ────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    // ── CRUD Modal ────────────────────────────────────────────────────────────
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->role      = $user->role;
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

        // Handle photo upload
        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('profile-images', 'public');
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->editingId);

            // Prevent changing default admin's role
            if ($user->email === $this->defaultAdminEmail) {
                $this->role = 'admin';
            }

            $data = [
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $this->role,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            if ($photoPath) {
                $data['profile_image'] = $photoPath;
            }

            $user->update($data);
            session()->flash('success', 'User updated successfully.');
        } else {
            $data = [
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
                'role'     => $this->role,
            ];

            if ($photoPath) {
                $data['profile_image'] = $photoPath;
            }

            User::create($data);
            session()->flash('success', 'User created successfully.');
        }

        $this->closeModal();
    }

    // ── Delete Modal ──────────────────────────────────────────────────────────
    public function openDeleteModal(int $id): void
    {
        $user = User::findOrFail($id);

        // Prevent deleting default admin
        if ($user->email === $this->defaultAdminEmail) {
            session()->flash('error', 'The default administrator account cannot be deleted.');
            return;
        }

        $this->deletingId       = $user->id;
        $this->deletingUserName = $user->name;
        $this->showDeleteModal  = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal  = false;
        $this->deletingId       = null;
        $this->deletingUserName = '';
    }

    public function confirmDelete(): void
    {
        if ($this->deletingId) {
            $user = User::findOrFail($this->deletingId);

            if ($user->email === $this->defaultAdminEmail) {
                session()->flash('error', 'The default administrator account cannot be deleted.');
                $this->closeDeleteModal();
                return;
            }

            $user->delete();
            session()->flash('success', 'User deleted successfully.');
            $this->closeDeleteModal();
        }
    }

    // ── Render ────────────────────────────────────────────────────────────────
    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
                $q->where('name',  'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(10);

        return view('livewire.user-manager', [
            'users'             => $users,
            'defaultAdminEmail' => $this->defaultAdminEmail,
        ])->layout('layouts.app');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function resetForm(): void
    {
        $this->name      = '';
        $this->email     = '';
        $this->password  = '';
        $this->role      = 'user';
        $this->photo     = null;
        $this->editingId = null;
        $this->isEditing = false;
    }
}