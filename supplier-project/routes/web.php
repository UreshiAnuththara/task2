<?php

use App\Livewire\SupplierManager;
use App\Livewire\UserManager;
use App\Livewire\ProfileSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ── Public: Auth Routes ────────────────────────────────────────────────────

// Login page — plain Blade view (NOT Livewire component)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('livewire.pages.auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('suppliers.index'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    });
});

// Breeze extra routes (register, password reset, etc.) — include if exists
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

// ── Authenticated Routes ───────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard redirect
    Route::get('/', function () {
        return redirect()->route('suppliers.index');
    })->name('home');

    Route::get('/dashboard', function () {
        return redirect()->route('suppliers.index');
    })->name('dashboard');

    // Supplier Management
    Route::get('/suppliers', SupplierManager::class)->name('suppliers.index');

    // User Management — admin only
    Route::get('/users', UserManager::class)
        ->name('users.index')
        ->middleware('can:admin-only');

    // Profile Settings — any authenticated user
    Route::get('/profile', ProfileSettings::class)->name('profile.settings');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});