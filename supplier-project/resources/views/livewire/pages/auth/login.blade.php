<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0d1117;
            --surface:   #111827;
            --surface-2: #1a2234;
            --border:    #1e2d45;
            --text:      #f1f5f9;
            --text-muted:#64748b;
            --accent:    #2563eb;
            --accent-h:  #1d4ed8;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            padding: 24px;
            background-image:
                radial-gradient(ellipse at 20% 20%, rgba(37,99,235,0.07) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(37,99,235,0.05) 0%, transparent 55%);
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,0.5);
            animation: fadeUp 0.35s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-top {
            padding: 36px 36px 28px;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }

        .logo-icon {
            width: 52px; height: 52px;
            background: var(--accent);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
        }
        .logo-icon svg { width: 26px; height: 26px; color: #fff; }

        .card-title    { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.4px; margin-bottom: 4px; }
        .card-subtitle { font-size: 13px; color: var(--text-muted); }

        .card-body { padding: 28px 36px 32px; }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 11.5px; font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 9px;
            color: var(--text);
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input::placeholder { color: var(--text-muted); opacity: 0.6; }
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        .form-error { font-size: 12px; color: #f87171; margin-top: 5px; }

        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 22px;
        }
        .remember-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
        .remember-row label { font-size: 13px; color: var(--text-muted); cursor: pointer; user-select: none; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--accent);
            color: #fff;
            border: none; border-radius: 10px;
            font-size: 15px; font-weight: 800;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            letter-spacing: -0.2px;
            transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
            box-shadow: 0 4px 16px rgba(37,99,235,0.3);
        }
        .btn-login:hover  { background: var(--accent-h); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
        .btn-login:active { transform: scale(0.98); }

        .alert-error {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 9px;
            font-size: 13px; color: #f87171;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .alert-error svg { flex-shrink: 0; margin-top: 1px; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-top">
        <div class="logo-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
        </div>
        <div class="card-title">Welcome back</div>
        <div class="card-subtitle">Sign in to your account to continue</div>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-input" type="email" id="email" name="email"
                       value="{{ old('email') }}" placeholder="you@example.com"
                       required autofocus>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password"
                       placeholder="Enter your password" required>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

    </div>
</div>

</body>
</html>