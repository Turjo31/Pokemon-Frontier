<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pokemon Frontier</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background: radial-gradient(circle at top, #0f172a, #020617);
        color: white;
    }

    /* NAVBAR */
    .navbar {
        background: rgba(17, 24, 39, 0.9);
        backdrop-filter: blur(10px);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #1f2937;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .logo {
        font-weight: 800;
        color: #38bdf8;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .nav-links {
        display: flex;
        gap: 20px;
        font-size: 14px;
        color: #94a3b8;
    }

    .nav-links div:hover {
        color: white;
        cursor: pointer;
    }

    /* PAGE */
    .container {
        padding: 35px;
    }

    h2 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #e2e8f0;
    }

    /* SEARCH */
    .search-box {
        width: 100%;
        max-width: 420px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid #1f2937;
        outline: none;
        background: rgba(31, 41, 55, 0.6);
        color: white;
        margin-bottom: 25px;
        transition: 0.3s;
    }

    .search-box:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);
    }

    /* GRID */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 22px;
    }

    /* CARD */
    .card {
        background: linear-gradient(145deg, #1e293b, #0f172a);
        border: 1px solid #1f2937;
        padding: 18px;
        border-radius: 18px;
        transition: 0.3s;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-8px);
        border-color: #38bdf8;
        box-shadow: 0 10px 25px rgba(56, 189, 248, 0.15);
    }

    /* POKEMON NAME */
    .pokemon-name {
        font-size: 18px;
        font-weight: 700;
        color: #38bdf8;
    }

    /* TYPE BADGE */
    .type {
        display: inline-block;
        margin-top: 8px;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(56, 189, 248, 0.15);
        color: #38bdf8;
    }

    /* STATS */
    .stats {
        margin-top: 12px;
        font-size: 13px;
        color: #cbd5e1;
        line-height: 1.6;
    }

    /* CARD GLOW EFFECT */
    .card::before {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(56,189,248,0.25), transparent);
        top: -30px;
        right: -30px;
        border-radius: 50%;
    }
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">⚡ Pokémon Frontier</div>
    <div class="nav-links">
        <div>Pokédex</div>
        <div>Teams</div>
        <div>Tournaments</div>
    </div>
</div>

<div class="container">
    @yield('content')
</div>

</body>
</html>