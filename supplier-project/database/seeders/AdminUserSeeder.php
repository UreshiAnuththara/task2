<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'ureshianuththara9@gmail.com'],
            [
                'name'              => 'Admin',
                'password'          => bcrypt('password'),
                'role'              => 'admin',
                'email_verified_at' => now(),   // ← Fix: verified by default
            ]
        );

        // Ensure role + verified status are always correct
        $updates = [];
        if ($user->role !== 'admin')              $updates['role']              = 'admin';
        if (is_null($user->email_verified_at))    $updates['email_verified_at'] = now();

        if (! empty($updates)) {
            $user->update($updates);
        }
    }
}