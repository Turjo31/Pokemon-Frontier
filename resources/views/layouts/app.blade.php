<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pokémon Frontier')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #080c14;
            --surface: #0d1420;
            --card: #111927;
            --border: rgba(255, 255, 255, 0.07);
            --border-hi: rgba(255, 255, 255, 0.14);
            --text: #e8edf5;
            --muted: #5a6a80;
            --accent: #e8372a;
            --accent2: #f5c842;

            --fire: #ff5e2e;
            --water: #3b9eff;
            --grass: #52c45a;
            --electric: #f7c948;
            --psychic: #e0408c;
            --ice: #7ad4e8;
            --dragon: #8b5cf6;
            --dark: #a78070;
            --normal: #8899aa;
            --poison: #b06cd0;
            --ground: #d4a855;
            --rock: #bba060;
            --ghost: #6060c8;
            --fighting: #e05040;
            --flying: #80aee8;
            --bug: #90c040;
            --steel: #9ab0c0;

            --display: 'Bebas Neue', sans-serif;
            --body: 'Outfit', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--body);
            background-color: var(--bg);
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(232, 55, 42, 0.12), transparent),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='64'%3E%3Cpath d='M28 0l28 16v32L28 64 0 48V16z' fill='none' stroke='rgba(255,255,255,0.025)' stroke-width='1'/%3E%3C/svg%3E");
            color: var(--text);
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(8, 12, 20, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 58px;
        }

        .nav-logo {
            font-family: var(--display);
            font-size: 24px;
            letter-spacing: 2px;
            color: var(--accent2);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-logo .dot {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.75);
            }
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-links a {
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 8px;
            letter-spacing: 0.3px;
            transition: color 0.2s, background 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--text);
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-links a.active {
            color: var(--accent2);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .trainer-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 5px 14px 5px 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .trainer-chip:hover {
            border-color: var(--border-hi);
        }

        .trainer-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }

        /* ── PAGE SHELL ── */
        .page-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 36px 32px 64px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-title {
            font-family: var(--display);
            font-size: 40px;
            letter-spacing: 2px;
            line-height: 1;
            color: var(--text);
        }

        .page-title span {
            color: var(--accent);
        }

        .page-sub {
            font-size: 13px;
            color: var(--muted);
            margin-top: 6px;
            font-weight: 400;
        }

        /* ── FLASH MESSAGES ── */
        .flash {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .flash-success {
            background: rgba(82, 196, 90, 0.1);
            border: 1px solid rgba(82, 196, 90, 0.3);
            color: #7ee382;
        }

        .flash-error {
            background: rgba(232, 55, 42, 0.1);
            border: 1px solid rgba(232, 55, 42, 0.3);
            color: #ff7c72;
        }

        /* ── TYPE COLOR SYSTEM ── */
        .type-fire {
            --tc: var(--fire);
        }

        .type-water {
            --tc: var(--water);
        }

        .type-grass {
            --tc: var(--grass);
        }

        .type-electric {
            --tc: var(--electric);
        }

        .type-psychic {
            --tc: var(--psychic);
        }

        .type-ice {
            --tc: var(--ice);
        }

        .type-dragon {
            --tc: var(--dragon);
        }

        .type-dark {
            --tc: var(--dark);
        }

        .type-normal {
            --tc: var(--normal);
        }

        .type-poison {
            --tc: var(--poison);
        }

        .type-ground {
            --tc: var(--ground);
        }

        .type-rock {
            --tc: var(--rock);
        }

        .type-ghost {
            --tc: var(--ghost);
        }

        .type-fighting {
            --tc: var(--fighting);
        }

        .type-flying {
            --tc: var(--flying);
        }

        .type-bug {
            --tc: var(--bug);
        }

        .type-steel {
            --tc: var(--steel);
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 9px;
            border-radius: 20px;
            background: color-mix(in srgb, var(--tc) 18%, transparent);
            color: var(--tc);
            border: 1px solid color-mix(in srgb, var(--tc) 30%, transparent);
        }

        /* ── STAT BARS ── */
        .stat-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .stat-label {
            width: 38px;
            color: var(--muted);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .stat-track {
            flex: 1;
            height: 4px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 4px;
            overflow: hidden;
        }

        .stat-fill {
            height: 100%;
            border-radius: 4px;
            background: var(--tc, var(--accent2));
            width: 0;
            transition: width 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-num {
            width: 28px;
            text-align: right;
            font-size: 11px;
            color: var(--muted);
            font-variant-numeric: tabular-nums;
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--body);
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
            border: none;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: #cc2e22;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: scale(0.97);
        }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            color: var(--text);
            border-color: var(--border-hi);
            background: rgba(255, 255, 255, 0.04);
        }

        /* ── FORMS ── */
        .input {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: var(--body);
            font-size: 14px;
            padding: 10px 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .input:focus {
            border-color: rgba(245, 200, 66, 0.5);
            box-shadow: 0 0 0 3px rgba(245, 200, 66, 0.08);
        }

        .input::placeholder {
            color: var(--muted);
        }

        /* ── CARDS ── */
        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .panel-head {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: 0.3px;
        }

        .panel-body {
            padding: 16px 18px;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeUp 0.4s ease both;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 16px;
            }

            .page-wrap {
                padding: 24px 16px 48px;
            }

            .page-title {
                font-size: 30px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar">
        <a href="/" class="nav-logo">
            <div class="dot"></div>
            POKÉMON FRONTIER
        </a>

        <div class="nav-links">
            <a href="/pokedex" class="{{ request()->is('pokedex*') ? 'active' : '' }}">Pokédex</a>
            <a href="/teams" class="{{ request()->is('teams*') ? 'active' : '' }}">My Teams</a>
            <a href="/tournaments" class="{{ request()->is('tournaments*') ? 'active' : '' }}">Tournaments</a>
            <a href="/leaderboard" class="{{ request()->is('leaderboard*') ? 'active' : '' }}">Leaderboard</a>
        </div>

        <div class="nav-right">
            @auth('trainer')
                <div class="trainer-chip">
                    <div class="trainer-avatar">{{ strtoupper(substr(auth('trainer')->user()->username, 0, 2)) }}</div>
                    {{ auth('trainer')->user()->username }}
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost" style="padding:6px 14px;font-size:12px">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary"
                    style="padding:6px 14px;font-size:12px">Register</a>
            @endauth
        </div>
    </nav>

    <div class="page-wrap">
        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>