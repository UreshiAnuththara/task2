<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inbizsys — Supplier Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <style>
        :root {
            --sidebar-w: 260px;
            --header-h: 64px;
            --bg: #0d1117;
            --sidebar-bg: #0a0e19;
            --surface: #111827;
            --surface-2: #1a2234;
            --border: #1e2d45;
            --text: #f1f5f9;
            --text-muted: #64748b;
            --text-dim: #334155;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-soft: rgba(37,99,235,0.12);
            --success: #22c55e;
            --danger: #ef4444;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ════════════════════ SIDEBAR ════════════════════ */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
        }

        .sidebar-brand-text .name {
            font-size: 16px; font-weight: 800; color: var(--text);
            letter-spacing: -0.3px;
        }

        .sidebar-brand-text .tagline {
            font-size: 10px; color: var(--text-muted); text-transform: uppercase;
            letter-spacing: 1.2px; font-weight: 600;
        }

        /* User info block */
        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border);
            flex-shrink: 0;
        }

        .sidebar-user-info .u-name {
            font-size: 14px; font-weight: 700; color: var(--text);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 140px;
        }

        .sidebar-user-info .u-role {
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.8px;
        }

        .role-admin { color: #f59e0b; }
        .role-user  { color: #60a5fa; }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-dim);
            padding: 0 8px;
            margin-bottom: 6px;
            margin-top: 16px;
        }

        .nav-section-label:first-child { margin-top: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: var(--surface-2);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--accent-soft);
            color: #60a5fa;
        }

        .nav-item.active .nav-icon { color: #3b82f6; }

        .nav-icon {
            width: 18px; height: 18px;
            flex-shrink: 0;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .logout-btn:hover {
            background: rgba(239,68,68,0.1);
            color: #f87171;
        }

        /* ════════════════════ MAIN CONTENT ════════════════════ */
        #main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        #top-header {
            height: var(--header-h);
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 50px;
            text-decoration: none;
        }

        .header-user-pill:hover { border-color: #2563eb; }

        .header-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .header-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        /* Page Content */
        #page-content {
            flex: 1;
            padding: 28px;
        }

        /* ════════════════════ HAMBURGER (mobile) ════════════════════ */
        #sidebar-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text);
            padding: 4px;
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #sidebar-toggle { display: flex; }
            #main-wrap { margin-left: 0; }
        }

        /* ════════════════════ UTILITIES ════════════════════ */
        .badge {
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-admin { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
        .badge-user  { background: rgba(96,165,250,0.12); color: #60a5fa; border: 1px solid rgba(96,165,250,0.25); }
    </style>
</head>
<body>

{{-- ═══ SIDEBAR ═══ --}}
<aside id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <svg width="22" height="22" fill="white" viewBox="0 0 24 24">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            <div class="name">Inbizsys</div>
            <div class="tagline">Management Suite</div>
        </div>
    </div>

    {{-- Logged-in user --}}
    @auth
    <div class="sidebar-user">
        <img src="{{ auth()->user()->profileImageUrl() }}"
             alt="{{ auth()->user()->name }}"
             class="sidebar-avatar">
        <div class="sidebar-user-info">
            <div class="u-name">{{ auth()->user()->name }}</div>
            <div class="u-role {{ auth()->user()->isAdmin() ? 'role-admin' : 'role-user' }}">
                {{ auth()->user()->isAdmin() ? '★ Administrator' : '● User' }}
            </div>
        </div>
    </div>
    @endauth

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- Administrator section (admin only) --}}
        @auth
        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">Administrator</div>

        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            System Users
        </a>
        @endif
        @endauth

        {{-- Master Data section --}}
        <div class="nav-section-label">Master Data</div>

        <a href="{{ route('suppliers.index') }}"
           class="nav-item {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Suppliers
        </a>

        {{-- Account section --}}
        <div class="nav-section-label">Account</div>

        <a href="{{ route('profile.settings') }}"
           class="nav-item {{ request()->routeIs('profile.settings') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            My Profile
        </a>

    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
        @endauth
    </div>
</aside>

{{-- ═══ MAIN CONTENT AREA ═══ --}}
<div id="main-wrap">

    {{-- Top Header --}}
    <header id="top-header">
        <div style="display:flex;align-items:center;gap:16px;">
            <button id="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="header-title">
                @if(request()->routeIs('suppliers.index')) Supplier Management
                @elseif(request()->routeIs('users.index')) User Management
                @elseif(request()->routeIs('profile.settings')) My Profile
                @else Dashboard
                @endif
            </span>
        </div>

        <div class="header-right">
            @auth
            <a href="{{ route('profile.settings') }}" class="header-user-pill">
                <img src="{{ auth()->user()->profileImageUrl() }}"
                     alt="{{ auth()->user()->name }}"
                     class="header-avatar">
                <span class="header-user-name">{{ auth()->user()->name }}</span>
                @if(auth()->user()->isAdmin())
                    <span class="badge badge-admin">Admin</span>
                @else
                    <span class="badge badge-user">User</span>
                @endif
            </a>
            @endauth
        </div>
    </header>

    {{-- Page Slot --}}
    <main id="page-content">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>php artisan migrate