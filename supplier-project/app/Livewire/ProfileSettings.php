<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileSettings extends Component
{
    use WithFileUploads;

    // ── Form Fields ───────────────────────────────────────────────────────────
    public string $name            = '';
    public string $email           = '';
    public string $current_password = '';
    public string $new_password     = '';
    public string $confirm_password = '';
    public $photo;

    public string $activeTab = 'profile'; // 'profile' | 'password'

    // ── Mount ─────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        $user        = auth()->user();
        $this->name  = $user->name;
        $this->email = $user->email;
    }

    // ── Profile Update ────────────────────────────────────────────────────────
    public function updateProfile(): void
    {
        $user = auth()->user();

        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
        ];

        if ($this->photo) {
            $data['profile_image'] = $this->photo->store('profile-images', 'public');
            $this->photo = null;
        }

        $user->update($data);

        session()->flash('profile_success', 'Profile updated successfully.');
    }

    // ── Password Change ───────────────────────────────────────────────────────
    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.different'  => 'New password must be different from current password.',
            'confirm_password.same'   => 'Passwords do not match.',
            'new_password.min'        => 'Password must be at least 8 characters.',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->update(['password' => Hash::make($this->new_password)]);

        $this->current_password = '';
        $this->new_password     = '';
        $this->confirm_password = '';

        session()->flash('password_success', 'Password changed successfully.');
    }

    // ── Render ────────────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.profile-settings')
            ->layout('layouts.app');
    }
}