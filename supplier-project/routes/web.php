<?php

use App\Livewire\SupplierManager;
use App\Livewire\UserManager;
use App\Livewire\RoleManager;
use App\Livewire\ProfileSettings;
use App\Livewire\LoginAnalytics;
use App\Livewire\DeviceApprovalManager;
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

// ── Device Pending Page ────────────────────────────────────────────────────
// Auth required (user must be logged in) but NOT the device check middleware.
// This is where users wait for admin approval.
Route::middleware(['auth'])->group(function () {
    Volt::route('/device-pending', 'pages.auth.device-pending')->name('device.pending');
});

// ── Authenticated + Device-Checked Routes ─────────────────────────────────
//
// Register middleware in bootstrap/app.php (Laravel 11) or Kernel.php (L10):
//   'check.device' => \App\Http\Middleware\CheckDeviceAuthorized::class
//
Route::middleware(['auth', 'check.device'])->group(function () {

    Route::get('/', fn() => redirect()->route('suppliers.index'))->name('home');
    Route::get('/dashboard', fn() => redirect()->route('suppliers.index'))->name('dashboard');

    Route::get('/suppliers', SupplierManager::class)->name('suppliers.index');
    Route::get('/users', UserManager::class)->name('users.index');
    Route::get('/roles', RoleManager::class)->name('roles.index');
    Route::get('/profile', ProfileSettings::class)->name('profile.settings');
    Route::get('/login-analytics', LoginAnalytics::class)->name('login.analytics');

    // ── Device Approvals — admin only (enforced inside the component) ──
    Route::get('/device-approvals', DeviceApprovalManager::class)->name('device-approvals.index');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->noContent();
    })->name('logout');
});