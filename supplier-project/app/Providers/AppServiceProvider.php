<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Gate: only admin role can access admin-only routes
        Gate::define('admin-only', function ($user) {
            return $user->isAdmin();
        });
    }
}