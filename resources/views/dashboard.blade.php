@extends('layouts.app')

@section('title', 'Dashboard — Pokémon Frontier')

@push('styles')
    <style>
        /* ── Stat cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
            animation: fadeUp 0.4s ease both;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--stat-color, var(--accent2));
        }

        .stat-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .stat-value {
            font-family: var(--display);
            font-size: 32px;
            letter-spacing: 1px;
            line-height: 1;
            color: var(--stat-color, var(--accent2));
        }

        .stat-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ── Two column layout ── */
        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        /* ── Panels ── */
        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            animation: fadeUp 0.4s ease both;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
        }

        .panel-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text);
        }

        .panel-link {
            font-size: 11px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .panel-link:hover {
            color: var(--accent2);
        }

        .panel-body {
            padding: 6px 0;
        }

        /* ── Match rows ── */
        .match-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            transition: background 0.15s;
        }

        .match-row:last-child {
            border-bottom: none;
        }

        .match-row:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .match-names {
            flex: 1;
        }

        .match-winner {
            font-weight: 500;
            color: var(--text);
        }

        .match-vs {
            font-size: 11px;
            color: var(--muted);
            margin-top: 1px;
        }

        .match-score {
            font-family: var(--display);
            font-size: 15px;
            letter-spacing: 1px;
            color: var(--accent2);
        }

        .badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .badge-win {
            background: rgba(82, 196, 90, 0.12);
            color: #7ee382;
        }

        .badge-loss {
            background: rgba(232, 55, 42, 0.1);
            color: #ff7c72;
        }

        /* ── Rank rows ── */
        .rank-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            transition: background 0.15s;
        }

        .rank-row:last-child {
            border-bottom: none;
        }

        .rank-row:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .rank-row.you {
            background: rgba(245, 200, 66, 0.04);
        }

        .rank-num {
            font-family: var(--display);
            font-size: 16px;
            width: 22px;
            text-align: center;
            color: var(--muted);
            flex-shrink: 0;
        }

        .rank-num.gold {
            color: #f5c842;
        }

        .rank-num.silver {
            color: #b0bec5;
        }

        .rank-num.bronze {
            color: #a1887f;
        }

        .rank-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .rank-name {
            flex: 1;
            font-weight: 500;
        }

        .rank-record {
            font-size: 11px;
            color: var(--muted);
        }

        .rank-pts {
            font-family: var(--display);
            font-size: 15px;
            color: var(--accent2);
        }

        /* ── Active tournament banner ── */
        .tournament-banner {
            background: var(--card);
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent2);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 14px;
            animation: fadeUp 0.4s ease both;
            animation-delay: 0.05s;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .tournament-banner:hover {
            background: rgba(245, 200, 66, 0.04);
            border-color: rgba(245, 200, 66, 0.4);
        }

        .tb-icon {
            font-size: 28px;
            flex-shrink: 0;
        }

        .tb-info {
            flex: 1;
        }

        .tb-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent2);
            margin-bottom: 3px;
        }

        .tb-name {
            font-family: var(--display);
            font-size: 18px;
            letter-spacing: 1px;
            color: var(--text);
            line-height: 1;
        }

        .tb-meta {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .tb-arrow {
            color: var(--muted);
            font-size: 18px;
        }

        /* ── Empty state ── */
        .empty {
            padding: 32px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        .empty-icon {
            font-size: 28px;
            margin-bottom: 8px;
            opacity: 0.4;
        }

        /* ── Animation delays ── */
        .stat-card:nth-child(1) {
            animation-delay: 0.00s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.05s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.10s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.15s;
        }

        .dash-grid .panel:nth-child(1) {
            animation-delay: 0.20s;
        }

        .dash-grid .panel:nth-child(2) {
            animation-delay: 0.25s;
        }

        @media (max-width: 900px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dash-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Page header --}}
    <div class="page-header animate-in">
        <div class="page-title">Dashboard</div>
        <div class="page-sub">Welcome back, {{ auth('trainer')->user()->username }} — Season 1 is active</div>
    </div>

    {{-- Stat cards --}}
    <div class="stat-grid">
        <div class="stat-card" style="--stat-color: var(--accent2)">
            <div class="stat-label">Rank points</div>
            <div class="stat-value">{{ $rankPoints ?? 0 }}</div>
            <div class="stat-sub">Season total</div>
        </div>
        <div class="stat-card" style="--stat-color: #52c45a">
            <div class="stat-label">Wins</div>
            <div class="stat-value">{{ $wins ?? 0 }}</div>
            <div class="stat-sub">{{ $losses ?? 0 }} losses</div>
        </div>
        <div class="stat-card" style="--stat-color: var(--accent)">
            <div class="stat-label">Global rank</div>
            <div class="stat-value">#{{ $globalRank ?? '—' }}</div>
            <div class="stat-sub">Out of {{ $totalTrainers ?? 0 }} trainers</div>
        </div>
        <div class="stat-card" style="--stat-color: #64b5f6">
            <div class="stat-label">Active teams</div>
            <div class="stat-value">{{ $teamCount ?? 0 }}</div>
            <div class="stat-sub"><a href="#" style="color:var(--muted);text-decoration:none">Manage teams →</a></div>
        </div>
    </div>

    {{-- Active tournament banner --}}
    @if(isset($activeTournament))
        <a href="#" class="tournament-banner">
            <div class="tb-icon">🏆</div>
            <div class="tb-info">
                <div class="tb-label">Active tournament</div>
                <div class="tb-name">{{ $activeTournament->name }}</div>
                <div class="tb-meta">{{ $activeTournament->league_name }} · Round in progress</div>
            </div>
            <div class="tb-arrow">›</div>
        </a>
    @endif

    {{-- Recent matches + Leaderboard --}}
    <div class="dash-grid">

        {{-- Recent battles --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Recent battles</span>
                <a href="#" class="panel-link">View all</a>
            </div>
            <div class="panel-body">
                @forelse($recentMatches ?? [] as $match)
                    @php $won = $match['winner_id'] == auth('trainer')->user()->trainer_id; @endphp
                    <div class="match-row">
                        <div class="match-names">
                            <div class="match-winner">{{ $match['opponent_name'] }}</div>
                            <div class="match-vs">{{ $match['tournament_name'] }}</div>
                        </div>
                        <div class="match-score">
                            {{ $match['my_score'] }}
                            <span style="color:var(--muted);font-size:11px">vs</span>
                            {{ $match['opp_score'] }}
                        </div>
                        <span class="badge {{ $won ? 'badge-win' : 'badge-loss' }}">
                            {{ $won ? 'Win' : 'Loss' }}
                        </span>
                    </div>
                @empty
                    <div class="empty">
                        <div class="empty-icon">⚔</div>
                        No matches yet — register for a tournament!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Leaderboard snapshot --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Top trainers</span>
                <a href="#" class="panel-link">Full board</a>
            </div>
            <div class="panel-body">
                @forelse($topRankings ?? [] as $i => $r)
                    @php
                        $numClass = match ($i) { 0 => 'gold', 1 => 'silver', 2 => 'bronze', default => ''};
                        $isYou = auth('trainer')->check() && $r['trainer_id'] == auth('trainer')->user()->trainer_id;
                        $colors = ['#7c3aed', '#0284c7', '#15803d', '#e8372a', '#b45309'];
                        $bg = $colors[$i % count($colors)];
                        $initials = strtoupper(substr($r['username'], 0, 2));
                    @endphp
                    <div class="rank-row {{ $isYou ? 'you' : '' }}">
                        <div class="rank-num {{ $numClass }}">{{ $i + 1 }}</div>
                        <div class="rank-avatar" style="background:{{ $bg }}">{{ $initials }}</div>
                        <div class="rank-name" style="{{ $isYou ? 'color:var(--accent2)' : '' }}">
                            {{ $r['username'] }}{{ $isYou ? ' (you)' : '' }}
                        </div>
                        <div class="rank-record">{{ $r['wins'] }}W {{ $r['losses'] }}L</div>
                        <div class="rank-pts">{{ number_format($r['points']) }}</div>
                    </div>
                @empty
                    <div class="empty">
                        <div class="empty-icon">🏅</div>
                        No rankings yet this season
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection