<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Pokémon Frontier</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #080c14; --card: #111927; --border: rgba(255,255,255,0.07);
            --text: #e8edf5; --muted: #8a9bb5; --accent: #e8372a; --accent2: #f5c842;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex;
            flex-direction: column; align-items: center; justify-content: center;
        }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand-logo { font-family: 'Bebas Neue', sans-serif; font-size: 32px; letter-spacing: 3px; color: var(--accent2); }
        .brand-sub { font-size: 11px; color: var(--muted); letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 36px 40px; width: 100%; max-width: 400px; }
        .card-title { font-family: 'Bebas Neue', sans-serif; font-size: 26px; letter-spacing: 1px; margin-bottom: 4px; }
        .card-sub { font-size: 13px; color: var(--muted); margin-bottom: 26px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--muted); margin-bottom: 7px; }
        .field input { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-family: 'Outfit', sans-serif; font-size: 14px; padding: 11px 14px; outline: none; transition: border-color 0.2s; }
        .field input:focus { border-color: rgba(245,200,66,0.5); }
        .field input::placeholder { color: var(--muted); }
        .error-box { background: rgba(232,55,42,0.08); border: 1px solid rgba(232,55,42,0.25); border-radius: 10px; padding: 10px 14px; font-size: 12px; color: #ff7c72; margin-bottom: 16px; }
        .btn-submit { width: 100%; background: var(--accent); color: #fff; border: none; border-radius: 10px; padding: 12px; font-size: 14px; font-weight: 600; font-family: 'Outfit', sans-serif; cursor: pointer; transition: background 0.18s; margin-top: 6px; }
        .btn-submit:hover { background: #cc2e22; }
    </style>
</head>
<body>
<div class="brand">
    <div class="brand-logo">POKÉMON FRONTIER</div>
    <div class="brand-sub">Admin Panel</div>
</div>
<div class="card">
    <div class="card-title">Admin login</div>
    <div class="card-sub">Restricted access — admins only</div>

    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="field">
            <label>Username</label>
            <input type="text" name="username" placeholder="admin" required autofocus>
        </div>
        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-submit">Sign in as admin</button>
    </form>
</div>
</body>
</html>