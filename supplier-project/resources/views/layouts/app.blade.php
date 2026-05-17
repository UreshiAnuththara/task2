<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inbizsys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            display: flex;
            min-height: 100vh;
        }

        /* ═══════ SIDEBAR ═══════ */
        #sidebar {
            width: 240px;
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
        }

        .sb-brand {
            padding: 18px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sb-logo {
            width: 36px; height: 36px;
            background: #2563eb;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sb-brand-name { font-size: 15px; font-weight: 800; color: #0f172a; }
        .sb-brand-sub  { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }

        /* User block */
        .sb-user {
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sb-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            flex-shrink: 0;
        }

        .sb-user-name  { font-size: 13px; font-weight: 700; color: #0f172a; }
        .sb-user-role  { font-size: 11px; font-weight: 600; }
        .role-admin    { color: #d97706; }
        .role-user     { color: #2563eb; }

        /* Nav */
        .sb-nav { flex: 1; padding: 12px 10px; overflow-y: auto; }

        .nav-label {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: #cbd5e1;
            padding: 0 10px;
            margin: 14px 0 4px;
        }

        .nav-label:first-child { margin-top: 0; }

        .nav-link {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px;
            border-radius: 7px;
            font-size: 13px; font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: background 0.12s, color 0.12s;
            margin-bottom: 1px;
        }

        .nav-link:hover  { background: #f8fafc; color: #0f172a; }
        .nav-link.active { background: #eff6ff; color: #2563eb; }
        .nav-link.active svg { color: #2563eb; }

        .nav-icon { width: 16px; height: 16px; flex-shrink: 0; }

        /* Footer */
        .sb-footer {
            padding: 12px 10px;
            border-top: 1px solid #f1f5f9;
        }

        .logout-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px;
            border-radius: 7px;
            font-size: 13px; font-weight: 600;
            color: #94a3b8;
            background: none; border: none;
            cursor: pointer; width: 100%;
            font-family: 'Figtree', sans-serif;
            transition: background 0.12s, color 0.12s;
        }

        .logout-btn:hover { background: #fef2f2; color: #ef4444; }

        /* ═══════ MAIN ═══════ */
        #main-wrap {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #top-bar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 50;
        }

        .top-title { font-size: 16px; font-weight: 800; color: #0f172a; }

        .top-user {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 12px 5px 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            text-decoration: none;
            transition: border-color 0.15s;
        }

        .top-user:hover { border-color: #2563eb; }

        .top-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; }
        .top-name   { font-size: 13px; font-weight: 700; color: #0f172a; }

        .badge {
            padding: 2px 8px; border-radius: 50px;
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
        }

        .badge-admin { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
        .badge-user  { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }

        #page-content {
            flex: 1;
            padding: 28px;
            overflow-y: auto;
            height: calc(100vh - 60px);
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); transition: transform 0.25s; }
            #sidebar.open { transform: translateX(0); }
            #main-wrap { margin-left: 0; }
        }

        /* ═══════ PAGINATION ═══════ */
        nav[aria-label="Pagination"] {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        nav[aria-label="Pagination"] span,
        nav[aria-label="Pagination"] a,
        nav[aria-label="Pagination"] button {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 7px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            font-family: 'Figtree', sans-serif !important;
            border: 1px solid #e2e8f0 !important;
            background: #fff !important;
            color: #374151 !important;
            text-decoration: none !important;
            transition: all 0.12s !important;
            box-shadow: none !important;
        }

        nav[aria-label="Pagination"] a:hover,
        nav[aria-label="Pagination"] button:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        nav[aria-label="Pagination"] span[aria-current="page"],
        nav[aria-label="Pagination"] span[aria-current="page"] span {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
            cursor: default !important;
        }

        nav[aria-label="Pagination"] span:not([aria-current]) > span {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #cbd5e1 !important;
            cursor: not-allowed !important;
        }

        nav[aria-label="Pagination"] svg {
            width: 14px !important;
            height: 14px !important;
        }
    </style>
</head>
<body>

{{-- ═══ SIDEBAR ═══ --}}
<aside id="sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <div class="sb-logo">
            <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </div>
        <div>
            <div class="sb-brand-name">Inbizsys</div>
            <div class="sb-brand-sub">Management Suite</div>
        </div>
    </div>

    {{-- User --}}
    @auth
    <div class="sb-user">
        <img src="{{ auth()->user()->profileImageUrl() }}" alt="" class="sb-avatar">
        <div>
            <div class="sb-user-name">{{ auth()->user()->name }}</div>
            <div class="sb-user-role {{ auth()->user()->isAdmin() ? 'role-admin' : 'role-user' }}">
                {{ auth()->user()->isAdmin() ? '★ Admin' : '● ' . (auth()->user()->role ?? 'User') }}
            </div>
        </div>
    </div>
    @endauth

    {{-- Nav --}}
    <nav class="sb-nav">

        {{-- Administrator section — admin only --}}
        @auth
        @if(auth()->user()->isAdmin())
        <div class="nav-label">Administrator</div>
        <a href="{{ route('users.index') }}" wire:navigate
           class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            User Management
        </a>

        <a href="{{ route('login.analytics') }}" wire:navigate
           class="nav-link {{ request()->routeIs('login.analytics') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Login Analytics
        </a>
        @endif
        @endauth

        {{-- Master Data --}}
        <div class="nav-label">Master Data</div>
        <a href="{{ route('suppliers.index') }}" wire:navigate
           class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Suppliers
        </a>

        {{-- Account --}}
        <div class="nav-label">Account</div>
        <a href="{{ route('profile.settings') }}" wire:navigate
           class="nav-link {{ request()->routeIs('profile.settings') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            My Profile
        </a>

    </nav>

    {{-- Sign Out --}}
    <div class="sb-footer">
        @auth
        <button
            class="logout-btn"
            onclick="
                fetch('{{ route('logout') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                }).finally(() => {
                    Livewire.navigate('/login');
                });
            "
        >
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Sign Out
        </button>
        @endauth
    </div>

</aside>

{{-- ═══ MAIN ═══ --}}
<div id="main-wrap">

    <header id="top-bar">
        <span class="top-title">
            @if(request()->routeIs('suppliers.index'))    Supplier Management
            @elseif(request()->routeIs('users.index'))    User Management
            @elseif(request()->routeIs('profile.settings')) My Profile
            @elseif(request()->routeIs('login.analytics')) Login Analytics
            @else Dashboard
            @endif
        </span>

        @auth
        <a href="{{ route('profile.settings') }}" wire:navigate class="top-user">
            <img src="{{ auth()->user()->profileImageUrl() }}" alt="" class="top-avatar">
            <span class="top-name">{{ auth()->user()->name }}</span>
            <span class="badge {{ auth()->user()->isAdmin() ? 'badge-admin' : 'badge-user' }}">
                {{ auth()->user()->isAdmin() ? 'Admin' : (auth()->user()->role ?? 'User') }}
            </span>
        </a>
        @endauth
    </header>

    <main id="page-content">
        {{ $slot }}
    </main>

</div>

@livewireScripts
@stack('scripts')
</body>
</html>