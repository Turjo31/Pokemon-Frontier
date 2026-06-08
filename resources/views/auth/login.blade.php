<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Pokémon Frontier</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #080c14;
            --surface:  #0d1420;
            --card:     #111927;
            --border:   rgba(255,255,255,0.07);
            --border-hi:rgba(255,255,255,0.14);
            --text:     #e8edf5;
            --muted:    #5a6a80;
            --accent:   #e8372a;
            --accent2:  #f5c842;
            --display:  'Bebas Neue', sans-serif;
            --body:     'Outfit', sans-serif;
        }

        body {
            font-family: var(--body);
            background-color: var(--bg);
            background-image:
                radial-gradient(ellipse 60% 50% at 50% -5%, rgba(232,55,42,0.1), transparent),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='64'%3E%3Cpath d='M28 0l28 16v32L28 64 0 48V16z' fill='none' stroke='rgba(255,255,255,0.022)' stroke-width='1'/%3E%3C/svg%3E");
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* ── Brand mark ── */
        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            font-family: var(--display);
            font-size: 36px;
            letter-spacing: 3px;
            color: var(--accent2);
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .brand-dot {
            width: 10px; height: 10px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.4; transform:scale(0.7); }
        }

        .brand-sub {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 6px;
            font-weight: 400;
        }

        /* ── Card ── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px 40px;
            width: 100%;
            max-width: 420px;
            animation: fadeUp 0.4s ease both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(14px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .card-title {
            font-family: var(--display);
            font-size: 28px;
            letter-spacing: 1.5px;
            color: var(--text);
            margin-bottom: 4px;
        }

        .card-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
        }

        /* ── Form ── */
        .field { margin-bottom: 18px; }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            margin-bottom: 7px;
        }

        .field input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: var(--body);
            font-size: 14px;
            padding: 11px 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field input:focus {
            border-color: rgba(245,200,66,0.5);
            box-shadow: 0 0 0 3px rgba(245,200,66,0.07);
        }

        .field input::placeholder { color: var(--muted); }

        .field-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .field-row label { margin-bottom: 0; }

        .forgot {
            font-size: 11px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot:hover { color: var(--accent2); }

        /* ── Error ── */
        .error-box {
            background: rgba(232,55,42,0.08);
            border: 1px solid rgba(232,55,42,0.25);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            color: #ff7c72;
            margin-bottom: 20px;
        }

        .error-box ul { padding-left: 16px; }
        .error-box li { margin-bottom: 2px; }

        /* ── Remember me ── */
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }

        .remember input[type="checkbox"] {
            appearance: none;
            width: 16px; height: 16px;
            border: 1px solid var(--border-hi);
            border-radius: 4px;
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.2s, border-color 0.2s;
            position: relative;
        }

        .remember input[type="checkbox"]:checked {
            background: var(--accent2);
            border-color: var(--accent2);
        }

        .remember input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 4px; top: 2px;
            width: 5px; height: 8px;
            border: 2px solid #111;
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        /* ── Submit ── */
        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--body);
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: background 0.18s, transform 0.12s;
        }

        .btn-submit:hover  { background: #cc2e22; transform: translateY(-1px); }
        .btn-submit:active { transform: scale(0.98); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: var(--muted);
            font-size: 11px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Footer ── */
        .card-footer {
            text-align: center;
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }

        .card-footer a {
            color: var(--accent2);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .card-footer a:hover { opacity: 0.8; }
    </style>
</head>
<body>

<div class="brand">
    <div class="brand-logo">
        <div class="brand-dot"></div>
        POKÉMON FRONTIER
    </div>
    <div class="brand-sub">Trainer Login</div>
</div>

<div class="card">
    <div class="card-title">Welcome back</div>
    <div class="card-sub">Sign in to your trainer account</div>

    @if($errors->any())
        <div class="error-box">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="trainer@example.com"
                required
                autofocus
            >
        </div>

        <div class="field">
            <div class="field-row">
                <label for="password">Password</label>
                <a href="#" class="forgot">Forgot password?</a>
            </div>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="••••••••"
                required
            >
        </div>

        <label class="remember">
            <input type="checkbox" name="remember"> Keep me signed in
        </label>

        <button type="submit" class="btn-submit">Sign in</button>
    </form>

    <div class="divider">or</div>

    <div class="card-footer">
        No account yet? <a href="{{ route('register') }}">Register as a trainer</a>
    </div>
</div>

</body>
</html>