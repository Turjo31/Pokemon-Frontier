@extends('layouts.app')

@section('title', 'Leaderboard — Pokémon Frontier')

@push('styles')
<style>
    /* ── Layout ── */
    .lb-wrap {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 16px;
        align-items: start;
    }

    /* ── League tabs ── */
    .league-tabs {
        display: flex;
        gap: 6px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .league-tab {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 6px 14px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--muted);
        cursor: pointer;
        font-family: var(--body);
        transition: all 0.15s;
    }

    .league-tab:hover  { color: var(--text); border-color: var(--border-hi); }
    .league-tab.active { background: rgba(245,200,66,0.1); border-color: rgba(245,200,66,0.35); color: var(--accent2); }

    /* ── Podium ── */
    .podium {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 8px;
        margin-bottom: 28px;
        padding: 0 12px;
    }

    .podium-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1;
        max-width: 140px;
    }

    .podium-avatar {
        width: 48px; height: 48px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; font-weight: 700; color: #fff;
        border: 2px solid transparent;
        flex-shrink: 0;
    }

    .podium-col.first .podium-avatar  { width: 56px; height: 56px; border-color: #f5c842; }
    .podium-col.second .podium-avatar { border-color: #b0bec5; }
    .podium-col.third .podium-avatar  { border-color: #a1887f; }

    .podium-name {
        font-size: 11px;
        font-weight: 600;
        color: var(--text);
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    .podium-pts {
        font-family: var(--display);
        font-size: 16px;
        letter-spacing: 1px;
        text-align: center;
    }

    .podium-col.first  .podium-pts { color: #f5c842; font-size: 20px; }
    .podium-col.second .podium-pts { color: #b0bec5; }
    .podium-col.third  .podium-pts { color: #a1887f; }

    .podium-block {
        width: 100%;
        border-radius: 10px 10px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .podium-place {
        font-family: var(--display);
        font-size: 22px;
        letter-spacing: 1px;
        padding: 10px 0;
    }

    .podium-col.first  .podium-block { background: rgba(245,200,66,0.12); height: 72px; }
    .podium-col.first  .podium-place { color: #f5c842; }
    .podium-col.second .podium-block { background: rgba(176,190,197,0.1); height: 52px; }
    .podium-col.second .podium-place { color: #b0bec5; }
    .podium-col.third  .podium-block { background: rgba(161,136,127,0.1); height: 38px; }
    .podium-col.third  .podium-place { color: #a1887f; }

    /* ── Main table ── */
    .lb-table {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        animation: fadeUp 0.4s ease both;
    }

    .lb-table-head {
        display: grid;
        grid-template-columns: 48px 1fr 80px 80px 100px;
        padding: 10px 18px;
        border-bottom: 1px solid var(--border);
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
    }

    .lb-row {
        display: grid;
        grid-template-columns: 48px 1fr 80px 80px 100px;
        padding: 11px 18px;
        border-bottom: 1px solid var(--border);
        align-items: center;
        transition: background 0.15s;
        animation: fadeUp 0.35s ease both;
    }

    .lb-row:last-child { border-bottom: none; }
    .lb-row:hover { background: rgba(255,255,255,0.02); }
    .lb-row.you   { background: rgba(245,200,66,0.04); }

    .lb-rank {
        font-family: var(--display);
        font-size: 18px;
        letter-spacing: 1px;
        color: var(--muted);
        text-align: center;
    }

    .lb-rank.gold   { color: #f5c842; }
    .lb-rank.silver { color: #b0bec5; }
    .lb-rank.bronze { color: #a1887f; }

    .lb-trainer {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .lb-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 600; color: #fff;
        flex-shrink: 0;
    }

    .lb-name {
        font-size: 13px;
        font-weight: 500;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lb-row.you .lb-name { color: var(--accent2); }

    .you-tag {
        font-size: 9px;
        font-weight: 600;
        background: rgba(245,200,66,0.12);
        color: var(--accent2);
        border: 1px solid rgba(245,200,66,0.25);
        border-radius: 20px;
        padding: 1px 7px;
        flex-shrink: 0;
    }

    .lb-cell {
        font-size: 13px;
        color: var(--muted);
        text-align: center;
    }

    .lb-cell.wins  { color: #7ee382; font-weight: 500; }
    .lb-cell.pts   {
        font-family: var(--display);
        font-size: 16px;
        letter-spacing: 1px;
        color: var(--accent2);
        text-align: right;
    }

    /* ── Right sidebar ── */
    .lb-sidebar { display: flex; flex-direction: column; gap: 12px; }

    .side-panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        animation: fadeUp 0.4s ease both;
    }

    .side-panel-head {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
    }

    .side-panel-body { padding: 12px 16px; }

    /* Your stats */
    .your-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .your-stat {
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 12px;
        text-align: center;
    }

    .your-stat-label { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: var(--muted); margin-bottom: 4px; }
    .your-stat-val   { font-family: var(--display); font-size: 20px; letter-spacing: 1px; color: var(--text); }
    .your-stat-val.gold { color: var(--accent2); }
    .your-stat-val.green{ color: #7ee382; }
    .your-stat-val.red  { color: var(--accent); }

    /* Streak */
    .streak-row {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .streak-dot {
        width: 20px; height: 20px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 9px; font-weight: 700;
    }

    .streak-dot.w { background: rgba(82,196,90,0.2); color: #7ee382; }
    .streak-dot.l { background: rgba(232,55,42,0.15); color: #ff7c72; }

    /* Empty */
    .empty-state {
        text-align: center;
        padding: 48px 0;
        color: var(--muted);
    }

    .empty-icon { font-size: 36px; opacity: 0.3; margin-bottom: 12px; }
    .empty-text { font-size: 13px; }

    @media (max-width: 900px) {
        .lb-wrap { grid-template-columns: 1fr; }
        .lb-table-head,
        .lb-row { grid-template-columns: 40px 1fr 60px 100px; }
        .lb-table-head > :nth-child(3),
        .lb-row > :nth-child(3) { display: none; }
    }
</style>
@endpush

@section('content')

<div class="page-header animate-in">
    <div class="page-title">Leader<span style="color:var(--accent)">board</span></div>
    <div class="page-sub">Season rankings · Updated after every match</div>
</div>

{{-- League tabs --}}
<div class="league-tabs animate-in" style="animation-delay:0.04s">
    @forelse($leagues ?? [] as $i => $league)
        <button class="league-tab {{ $i === 0 ? 'active' : '' }}"
            data-league="{{ $league['id'] }}">
            {{ $league['name'] }}
        </button>
    @empty
        <button class="league-tab active">Season 1</button>
    @endforelse
</div>

<div class="lb-wrap">

    {{-- ── Main leaderboard ── --}}
    <div>

        {{-- Podium (top 3) --}}
        @if(count($rankings ?? []) >= 3)
        <div class="podium animate-in" style="animation-delay:0.08s">
            @php
                $colors = ['#7c3aed','#0284c7','#15803d','#e8372a','#b45309','#0f766e'];
                $r      = $rankings;
            @endphp

            {{-- 2nd place --}}
            <div class="podium-col second">
                <div class="podium-avatar" style="background:{{ $colors[1] }}">
                    {{ strtoupper(substr($r[1]['username'] ?? 'T', 0, 2)) }}
                </div>
                <div class="podium-name">{{ $r[1]['username'] ?? 'Trainer' }}</div>
                <div class="podium-pts">{{ number_format($r[1]['points'] ?? 0) }}</div>
                <div class="podium-block"><span class="podium-place">2</span></div>
            </div>

            {{-- 1st place --}}
            <div class="podium-col first">
                <div class="podium-avatar" style="background:{{ $colors[0] }}">
                    {{ strtoupper(substr($r[0]['username'] ?? 'T', 0, 2)) }}
                </div>
                <div class="podium-name">{{ $r[0]['username'] ?? 'Trainer' }}</div>
                <div class="podium-pts">{{ number_format($r[0]['points'] ?? 0) }}</div>
                <div class="podium-block"><span class="podium-place">1</span></div>
            </div>

            {{-- 3rd place --}}
            <div class="podium-col third">
                <div class="podium-avatar" style="background:{{ $colors[2] }}">
                    {{ strtoupper(substr($r[2]['username'] ?? 'T', 0, 2)) }}
                </div>
                <div class="podium-name">{{ $r[2]['username'] ?? 'Trainer' }}</div>
                <div class="podium-pts">{{ number_format($r[2]['points'] ?? 0) }}</div>
                <div class="podium-block"><span class="podium-place">3</span></div>
            </div>
        </div>
        @endif

        {{-- Full table --}}
        <div class="lb-table">
            <div class="lb-table-head">
                <span style="text-align:center">#</span>
                <span>Trainer</span>
                <span style="text-align:center">W / L</span>
                <span style="text-align:center">Matches</span>
                <span style="text-align:right">Points</span>
            </div>

            @php $colors = ['#7c3aed','#0284c7','#15803d','#e8372a','#b45309','#0f766e']; @endphp
            
            @forelse($rankings ?? [] as $i => $r)
                @php
                    $rankClass = match($i) { 0=>'gold', 1=>'silver', 2=>'bronze', default=>'' };
                    $isYou     = false; // replace with auth check when auth is built
                    $bg        = $colors[$i % count($colors)];
                    $initials  = strtoupper(substr($r['username'] ?? 'T', 0, 2));
                    $matches   = ($r['wins'] ?? 0) + ($r['losses'] ?? 0);
                @endphp
                <div class="lb-row {{ $isYou ? 'you' : '' }}" style="animation-delay:{{ $i * 0.03 }}s">
                    <div class="lb-rank {{ $rankClass }}">{{ $i + 1 }}</div>
                    <div class="lb-trainer">
                        <div class="lb-avatar" style="background:{{ $bg }}">{{ $initials }}</div>
                        <span class="lb-name">{{ $r['username'] ?? 'Trainer' }}</span>
                        @if($isYou)<span class="you-tag">you</span>@endif
                    </div>
                    <div class="lb-cell">
                        <span class="wins">{{ $r['wins'] ?? 0 }}W</span>
                        <span style="color:var(--border-hi)"> / </span>
                        {{ $r['losses'] ?? 0 }}L
                    </div>
                    <div class="lb-cell">{{ $matches }}</div>
                    <div class="lb-cell pts">{{ number_format($r['points'] ?? 0) }}</div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">🏅</div>
                    <div class="empty-text">No rankings yet — play some matches!</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ── Sidebar ── --}}
    <div class="lb-sidebar">

        {{-- Your standing --}}
        <div class="side-panel" style="animation-delay:0.1s">
            <div class="side-panel-head">Your standing</div>
            <div class="side-panel-body">
                <div class="your-stats-grid">
                    <div class="your-stat">
                        <div class="your-stat-label">Rank</div>
                        <div class="your-stat-val gold">#{{ $myRank ?? '—' }}</div>
                    </div>
                    <div class="your-stat">
                        <div class="your-stat-label">Points</div>
                        <div class="your-stat-val gold">{{ number_format($myPoints ?? 0) }}</div>
                    </div>
                    <div class="your-stat">
                        <div class="your-stat-label">Wins</div>
                        <div class="your-stat-val green">{{ $myWins ?? 0 }}</div>
                    </div>
                    <div class="your-stat">
                        <div class="your-stat-label">Losses</div>
                        <div class="your-stat-val red">{{ $myLosses ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent form --}}
        <div class="side-panel" style="animation-delay:0.15s">
            <div class="side-panel-head">Recent form</div>
            <div class="side-panel-body">
                @if(count($recentForm ?? []) > 0)
                    <div style="font-size:11px;color:var(--muted);margin-bottom:8px">Last {{ count($recentForm) }} matches</div>
                    <div class="streak-row">
                        @foreach($recentForm as $result)
                            <div class="streak-dot {{ $result === 'W' ? 'w' : 'l' }}">{{ $result }}</div>
                        @endforeach
                    </div>
                @else
                    <div style="font-size:12px;color:var(--muted);text-align:center;padding:12px 0">
                        No matches played yet
                    </div>
                @endif
            </div>
        </div>

        {{-- Season info --}}
        <div class="side-panel" style="animation-delay:0.2s">
            <div class="side-panel-head">Season info</div>
            <div class="side-panel-body" style="font-size:12px;color:var(--muted);line-height:2">
                <div style="display:flex;justify-content:space-between">
                    <span>Season</span>
                    <span style="color:var(--text)">{{ $season ?? 'Season 1' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span>Total trainers</span>
                    <span style="color:var(--text)">{{ count($rankings ?? []) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span>Matches played</span>
                    <span style="color:var(--text)">{{ $totalMatches ?? 0 }}</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span>Points to #1</span>
                    <span style="color:var(--accent2)">
                        {{ isset($rankings[0]['points'], $myPoints) && $myPoints < $rankings[0]['points']
                            ? '+' . number_format($rankings[0]['points'] - $myPoints)
                            : '—' }}
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.league-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.league-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        // When controller is built: fetch rankings for tab.dataset.league and re-render
    });
});
</script>
@endpush

@endsection