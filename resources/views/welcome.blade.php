<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Frontier — Compete. Conquer. Rise.</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #080c14;
            --surface: #0d1420;
            --card:    #111927;
            --border:  rgba(255,255,255,0.07);
            --text:    #e8edf5;
            --muted:   #8a9bb5;
            --accent:  #e8372a;
            --accent2: #f5c842;
            --display: 'Bebas Neue', sans-serif;
            --body:    'Outfit', sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--body);
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Navbar ── */
        .nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            height: 60px;
            background: rgba(8,12,20,0);
            transition: background 0.3s, border-color 0.3s;
            border-bottom: 1px solid transparent;
        }

        .nav.scrolled {
            background: rgba(8,12,20,0.92);
            backdrop-filter: blur(16px);
            border-color: var(--border);
        }

        .nav-logo {
            font-family: var(--display);
            font-size: 22px;
            letter-spacing: 2px;
            color: var(--accent2);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .nav-dot {
            width: 8px; height: 8px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.4; transform:scale(0.7); }
        }

        .nav-links { display: flex; align-items: center; gap: 8px; }

        .nav-login {
            font-size: 13px; font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: 7px 16px;
            border-radius: 8px;
            transition: color 0.2s;
        }

        .nav-login:hover { color: var(--text); }

        .nav-cta {
            font-size: 13px; font-weight: 600;
            color: #fff;
            text-decoration: none;
            background: var(--accent);
            padding: 7px 18px;
            border-radius: 8px;
            transition: background 0.18s, transform 0.12s;
        }

        .nav-cta:hover { background: #cc2e22; transform: translateY(-1px); }

        /* ── Hero ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 100px 24px 60px;
            position: relative;
            overflow: hidden;
        }

        /* hex grid bg */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='64'%3E%3Cpath d='M28 0l28 16v32L28 64 0 48V16z' fill='none' stroke='rgba(255,255,255,0.03)' stroke-width='1'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* red glow top */
        .hero::after {
            content: '';
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 60%;
            background: radial-gradient(ellipse, rgba(232,55,42,0.14) 0%, transparent 65%);
            pointer-events: none;
        }

        .hero-inner { position: relative; z-index: 2; max-width: 780px; }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent2);
            background: rgba(245,200,66,0.08);
            border: 1px solid rgba(245,200,66,0.2);
            border-radius: 20px;
            padding: 5px 14px;
            margin-bottom: 24px;
        }

        .hero-eyebrow-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--accent2);
            animation: pulse 2s ease-in-out infinite;
        }

        .hero-title {
            font-family: var(--display);
            font-size: clamp(64px, 10vw, 110px);
            letter-spacing: 4px;
            line-height: 0.92;
            color: var(--text);
            margin-bottom: 8px;
        }

        .hero-title .red { color: var(--accent); }

        .hero-tagline {
            font-family: var(--display);
            font-size: clamp(18px, 3vw, 28px);
            letter-spacing: 4px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .hero-desc {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 480px;
            margin: 0 auto 40px;
            font-weight: 300;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            font-size: 14px; font-weight: 600;
            color: #fff;
            background: var(--accent);
            text-decoration: none;
            padding: 13px 32px;
            border-radius: 10px;
            letter-spacing: 0.4px;
            transition: background 0.18s, transform 0.12s;
        }

        .btn-hero-primary:hover { background: #cc2e22; transform: translateY(-2px); }

        .btn-hero-ghost {
            font-size: 14px; font-weight: 500;
            color: var(--muted);
            background: transparent;
            text-decoration: none;
            padding: 13px 32px;
            border-radius: 10px;
            border: 1px solid var(--border);
            letter-spacing: 0.4px;
            transition: all 0.18s;
        }

        .btn-hero-ghost:hover { color: var(--text); border-color: var(--border-hi); }

        /* ── Stats bar ── */
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-top: 64px;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }

        .stats-bar-item {
            flex: 1;
            max-width: 200px;
            text-align: center;
            padding: 24px 16px;
            border-right: 1px solid var(--border);
        }

        .stats-bar-item:last-child { border-right: none; }

        .stats-bar-val {
            font-family: var(--display);
            font-size: 36px;
            letter-spacing: 2px;
            color: var(--accent2);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stats-bar-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
        }

        /* ── Features section ── */
        .section {
            max-width: 1100px;
            margin: 0 auto;
            padding: 96px 24px;
        }

        .section-eyebrow {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent2);
            margin-bottom: 12px;
        }

        .section-title {
            font-family: var(--display);
            font-size: clamp(36px, 5vw, 52px);
            letter-spacing: 2px;
            color: var(--text);
            line-height: 1;
            margin-bottom: 48px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .feature-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            transition: border-color 0.2s, transform 0.2s;
        }

        .feature-card:hover {
            border-color: var(--border-hi);
            transform: translateY(-3px);
        }

        .feature-icon {
            font-size: 28px;
            margin-bottom: 14px;
            display: block;
        }

        .feature-title {
            font-family: var(--display);
            font-size: 20px;
            letter-spacing: 1px;
            color: var(--text);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ── How it works ── */
        .how-section {
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .steps-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }

        .step-item {
            padding: 40px 28px;
            border-right: 1px solid var(--border);
            position: relative;
        }

        .step-item:last-child { border-right: none; }

        .step-num {
            font-family: var(--display);
            font-size: 48px;
            letter-spacing: 2px;
            color: rgba(232,55,42,0.45);
            line-height: 1;
            margin-bottom: 12px;
        }

        .step-title {
            font-family: var(--display);
            font-size: 18px;
            letter-spacing: 1px;
            color: var(--text);
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ── CTA section ── */
        .cta-section {
            text-align: center;
            padding: 96px 24px;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            bottom: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 60%;
            background: radial-gradient(ellipse, rgba(232,55,42,0.1) 0%, transparent 65%);
            pointer-events: none;
        }

        .cta-title {
            font-family: var(--display);
            font-size: clamp(40px, 6vw, 64px);
            letter-spacing: 3px;
            color: var(--text);
            line-height: 1;
            margin-bottom: 16px;
        }

        .cta-title .red { color: var(--accent); }

        .cta-sub {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 36px;
            font-weight: 300;
        }

        /* ── Footer ── */
        .footer {
            border-top: 1px solid var(--border);
            padding: 24px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--muted);
        }

        .footer-logo {
            font-family: var(--display);
            font-size: 16px;
            letter-spacing: 2px;
            color: var(--accent2);
        }

        @media (max-width: 768px) {
            .nav { padding: 0 20px; }
            .features-grid { grid-template-columns: 1fr; }
            .steps-row { grid-template-columns: 1fr 1fr; }
            .step-item:nth-child(2) { border-right: none; }
            .stats-bar { flex-wrap: wrap; }
            .stats-bar-item { min-width: 120px; }
            .footer { flex-direction: column; gap: 8px; text-align: center; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="nav" id="nav">
    <a href="/" class="nav-logo">
        <div class="nav-dot"></div>
        POKÉMON FRONTIER
    </a>
    <div class="nav-links">
        <a href="{{ route('login') }}"    class="nav-login">Sign in</a>
        <a href="{{ route('register') }}" class="nav-cta">Get started</a>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-eyebrow">
            <div class="hero-eyebrow-dot"></div>
            Tournament season is live
        </div>
        <h1 class="hero-title">
            POKÉMON<br><span class="red">FRONTIER</span>
        </h1>
        <div class="hero-tagline">COMPETE · CONQUER · RISE</div>
        <p class="hero-desc">
            Build your ultimate team, register for tournaments,
            and battle your way to the top of the leaderboard.
            Every match is simulated — only raw power and strategy win.
        </p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn-hero-primary">Start your journey</a>
            <a href="{{ route('login') }}"    class="btn-hero-ghost">Sign in</a>
        </div>

        <div class="stats-bar">
            <div class="stats-bar-item">
                <div class="stats-bar-val">151</div>
                <div class="stats-bar-label">Pokémon</div>
            </div>
            <div class="stats-bar-item">
                <div class="stats-bar-val">{{ $trainerCount ?? '0' }}</div>
                <div class="stats-bar-label">Trainers</div>
            </div>
            <div class="stats-bar-item">
                <div class="stats-bar-val">{{ $matchCount ?? '0' }}</div>
                <div class="stats-bar-label">Matches played</div>
            </div>
            <div class="stats-bar-item">
                <div class="stats-bar-val">{{ $tournamentCount ?? '0' }}</div>
                <div class="stats-bar-label">Tournaments</div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="section">
    <div class="section-eyebrow">What you can do</div>
    <div class="section-title">EVERYTHING A<br>TRAINER NEEDS</div>

    <div class="features-grid">
        <div class="feature-card">
            <span class="feature-icon">📖</span>
            <div class="feature-title">Full Pokédex</div>
            <div class="feature-desc">Browse all available Pokémon, filter by type, and compare base stats before building your roster.</div>
        </div>
        <div class="feature-card">
            <span class="feature-icon">⚔️</span>
            <div class="feature-title">Team Builder</div>
            <div class="feature-desc">Pick up to 6 Pokémon, name your squad, and save multiple teams to register in different tournaments.</div>
        </div>
        <div class="feature-card">
            <span class="feature-icon">🏆</span>
            <div class="feature-title">Tournaments</div>
            <div class="feature-desc">Join open tournaments across different leagues. Single elimination, round robin — all formats supported.</div>
        </div>
        <div class="feature-card">
            <span class="feature-icon">⚡</span>
            <div class="feature-title">Auto Battles</div>
            <div class="feature-desc">Matches are simulated by total stat power with a random variance applied — no manual input needed.</div>
        </div>
        <div class="feature-card">
            <span class="feature-icon">📊</span>
            <div class="feature-title">Live Rankings</div>
            <div class="feature-desc">Leaderboards update automatically after every match via database triggers. Your rank is always current.</div>
        </div>
        <div class="feature-card">
            <span class="feature-icon">🎯</span>
            <div class="feature-title">Match History</div>
            <div class="feature-desc">Review every battle result — scores, opponents, teams used, and how each match affected your ranking.</div>
        </div>
    </div>
</section>

{{-- How it works --}}
<div class="how-section">
    <div class="section" style="padding-top:64px;padding-bottom:64px">
        <div class="section-eyebrow">How it works</div>
        <div class="section-title" style="margin-bottom:36px">FOUR STEPS TO<br>THE TOP</div>
        <div class="steps-row">
            <div class="step-item">
                <div class="step-num">01</div>
                <div class="step-title">Register</div>
                <div class="step-desc">Create a trainer account with your name and email. Takes under a minute.</div>
            </div>
            <div class="step-item">
                <div class="step-num">02</div>
                <div class="step-title">Build a team</div>
                <div class="step-desc">Browse the Pokédex and pick up to 6 Pokémon. Name your squad and save it.</div>
            </div>
            <div class="step-item">
                <div class="step-num">03</div>
                <div class="step-title">Enter a tournament</div>
                <div class="step-desc">Find an open tournament and register your team. Wait for the admin to start the round.</div>
            </div>
            <div class="step-item">
                <div class="step-num">04</div>
                <div class="step-title">Climb the ranks</div>
                <div class="step-desc">Matches simulate automatically. Win to earn points and rise on the leaderboard.</div>
            </div>
        </div>
    </div>
</div>

{{-- CTA --}}
<section class="cta-section">
    <div class="cta-title">READY TO<br><span class="red">COMPETE?</span></div>
    <div class="cta-sub">Join the frontier. Build your team. Prove your strength.</div>
    <a href="{{ route('register') }}" class="btn-hero-primary" style="font-size:15px;padding:14px 40px">
        Create your trainer account
    </a>
</section>

{{-- Footer --}}
<footer class="footer">
    <div class="footer-logo">POKÉMON FRONTIER</div>
    <div>By Turjo &nbsp;·&nbsp; Built with Laravel + Oracle</div>
</footer>

<script>
    const nav = document.getElementById('nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });
</script>

</body>
</html>