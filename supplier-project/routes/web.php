<?php

use App\Livewire\SupplierManager;
use App\Livewire\UserManager;
use App\Livewire\ProfileSettings;
use App\Livewire\LoginAnalytics;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Volt\Volt;

// ── Guest Routes ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Volt::route('/login', 'pages.auth.login')->name('login');
    Volt::route('/register', 'pages.auth.register')->name('register');
});

// ── Extra Auth Routes ──────────────────────────────────────────────────────
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

// ── Authenticated Routes ───────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    Route::get('/', fn() => redirect()->route('suppliers.index'))->name('home');
    Route::get('/dashboard', fn() => redirect()->route('suppliers.index'))->name('dashboard');

    Route::get('/suppliers', SupplierManager::class)->name('suppliers.index');
    Route::get('/users', UserManager::class)->name('users.index');
    Route::get('/profile', ProfileSettings::class)->name('profile.settings');
    Route::get('/login-analytics', LoginAnalytics::class)->name('login.analytics');

    // FIX: Logout returns a plain 200 (no redirect) so the fetch() in app.blade.php
    // completes cleanly, then Livewire.navigate('/login') handles the SPA transition
    // without a hard browser reload.
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->noContent(); 
    })->name('logout');
});