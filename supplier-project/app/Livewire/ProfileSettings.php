<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileSettings extends Component
{
    use WithFileUploads;

    public string $name             = '';
    public string $email            = '';
    public string $current_password = '';
    public string $new_password     = '';
    public string $confirm_password = '';
    public $photo;
    public string $activeTab = 'profile';

    public function mount(): void
    {
        $this->name  = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function updateProfile(): void
    {
        $user = auth()->user();
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $this->name, 'email' => $this->email];
        if ($this->photo) {
            $data['profile_image'] = $this->photo->store('profile-images', 'public');
            $this->reset('photo');
        }
        $user->update($data);
        session()->flash('profile_ok', 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:6|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.different'  => 'New password must differ from current.',
            'confirm_password.same'   => 'Passwords do not match.',
        ]);

        if (! Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        auth()->user()->update(['password' => Hash::make($this->new_password)]);
        $this->current_password = $this->new_password = $this->confirm_password = '';
        session()->flash('password_ok', 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.profile-settings')->layout('layouts.app');
    }
}