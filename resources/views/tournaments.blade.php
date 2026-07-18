@extends('layouts.app')

@section('title', 'Tournaments — Pokémon Frontier')

@push('styles')
<style>
    /* ── Filter bar ── */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-tab {
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

    .filter-tab:hover { color: var(--text); border-color: var(--border-hi); }
    .filter-tab.active { background: rgba(245,200,66,0.1); border-color: rgba(245,200,66,0.35); color: var(--accent2); }

    .filter-bar-right {
        margin-left: auto;
        font-size: 12px;
        color: var(--muted);
    }

    /* ── Tournament list ── */
    .tournament-list { display: flex; flex-direction: column; gap: 10px; }

    /* ── Tournament card ── */
    .t-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.18s;
        animation: fadeUp 0.35s ease both;
    }

    .t-card:hover { border-color: var(--border-hi); }
    .t-card.hidden { display: none; }

    .t-card-main {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        cursor: pointer;
    }

    .t-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(255,255,255,0.05);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .t-main-info { flex: 1; min-width: 0; }

    .t-name {
        font-family: var(--display);
        font-size: 20px;
        letter-spacing: 1px;
        color: var(--text);
        line-height: 1;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .t-meta {
        font-size: 12px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .t-meta-item { display: flex; align-items: center; gap: 4px; }

    .t-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    /* Status badges */
    .t-status {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .status-open     { background: rgba(82,196,90,0.12);  color: #7ee382; border: 1px solid rgba(82,196,90,0.25); }
    .status-active   { background: rgba(245,200,66,0.12); color: #f5d060; border: 1px solid rgba(245,200,66,0.25); }
    .status-full     { background: rgba(255,255,255,0.06); color: var(--muted); border: 1px solid var(--border); }
    .status-finished { background: rgba(255,255,255,0.04); color: var(--muted); border: 1px solid var(--border); }

    /* Expand chevron */
    .t-chevron {
        color: var(--muted);
        font-size: 18px;
        transition: transform 0.25s;
        line-height: 1;
        user-select: none;
    }

    .t-card.expanded .t-chevron { transform: rotate(180deg); }

    /* Participants bar */
    .participants-wrap { padding: 0 20px 4px; }

    .participants-label {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--muted);
        margin-bottom: 6px;
    }

    .participants-track {
        height: 3px;
        background: rgba(255,255,255,0.06);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 14px;
    }

    .participants-fill {
        height: 100%;
        border-radius: 3px;
        background: var(--accent2);
        transition: width 0.6s cubic-bezier(0.4,0,0.2,1);
    }

    /* Expanded detail */
    .t-detail {
        display: none;
        border-top: 1px solid var(--border);
        padding: 18px 20px;
        gap: 20px;
    }

    .t-card.expanded .t-detail { display: flex; }

    .t-detail-left { flex: 1; }
    .t-detail-right { width: 200px; flex-shrink: 0; }

    .detail-section { margin-bottom: 16px; }

    .detail-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 13px;
        color: var(--text);
        line-height: 1.6;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .detail-stat {
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
    }

    .detail-stat-label { font-size: 10px; color: var(--muted); margin-bottom: 3px; }
    .detail-stat-val   { font-family: var(--display); font-size: 20px; letter-spacing: 1px; color: var(--text); }

    /* Register button in expanded */
    .btn-register {
        display: block;
        width: 100%;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--body);
        letter-spacing: 0.4px;
        cursor: pointer;
        transition: background 0.18s, transform 0.12s;
        text-align: center;
        text-decoration: none;
        margin-top: 8px;
    }

    .btn-register:hover  { background: #cc2e22; transform: translateY(-1px); }
    .btn-register:active { transform: scale(0.98); }

    .btn-registered {
        display: block;
        width: 100%;
        background: rgba(82,196,90,0.1);
        color: #7ee382;
        border: 1px solid rgba(82,196,90,0.25);
        border-radius: 10px;
        padding: 11px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--body);
        text-align: center;
        cursor: default;
        margin-top: 8px;
    }

    .btn-disabled {
        display: block;
        width: 100%;
        background: rgba(255,255,255,0.05);
        color: var(--muted);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 11px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--body);
        text-align: center;
        cursor: not-allowed;
        margin-top: 8px;
    }

    /* Team select in expanded */
    .team-select {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: var(--body);
        font-size: 13px;
        padding: 9px 12px;
        outline: none;
        margin-bottom: 0;
        transition: border-color 0.2s;
        appearance: none;
        cursor: pointer;
    }

    .team-select:focus { border-color: rgba(245,200,66,0.4); }

    .select-wrap { position: relative; }
    .select-wrap::after {
        content: '▾';
        position: absolute;
        right: 12px; top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
        font-size: 12px;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 64px 0;
        color: var(--muted);
    }

    .empty-icon { font-size: 40px; margin-bottom: 14px; opacity: 0.35; }
    .empty-text { font-size: 14px; }
    .empty-sub  { font-size: 12px; margin-top: 6px; }
</style>
@endpush

@section('content')

<div class="page-header animate-in">
    <div class="page-title">Tourna<span style="color:var(--accent)">ments</span></div>
    <div class="page-sub">Register your team · Compete · Climb the ranks</div>
</div>

{{-- Filter bar --}}
<div class="filter-bar animate-in" style="animation-delay:0.05s">
    <button class="filter-tab active" data-filter="all">All</button>
    <button class="filter-tab" data-filter="open">Open</button>
    <button class="filter-tab" data-filter="active">In progress</button>
    <button class="filter-tab" data-filter="finished">Finished</button>
    <span class="filter-bar-right" id="t-count"></span>
</div>

<div class="tournament-list" id="tournament-list">

    @forelse($tournaments ?? [] as $i => $t)
        @php
            $status    = strtolower($t['status'] ?? 'open');
            $filled    = $t['registered'] ?? 0;
            $max       = $t['max_participants'] ?? 16;
            $pct       = $max > 0 ? min(100, round($filled / $max * 100)) : 0;
            $icons     = ['🏆','⚔️','🌟','🔥','⚡','🎯'];
            $icon      = $icons[$i % count($icons)];
            $registered = $t['is_registered'] ?? false;
        @endphp

        <div class="t-card" data-status="{{ $status }}" style="animation-delay:{{ $i * 0.05 }}s">

            {{-- Main row --}}
            <div class="t-card-main" onclick="toggleCard(this.closest('.t-card'))">
                <div class="t-icon">{{ $icon }}</div>
                <div class="t-main-info">
                    <div class="t-name">{{ $t['name'] }}</div>
                    <div class="t-meta">
                        <span class="t-meta-item">🏟 {{ $t['league_name'] ?? 'Season 1' }}</span>
                        <span class="t-meta-item">👥 {{ $filled }}/{{ $max }} trainers</span>
                        <span class="t-meta-item">📋 {{ ucfirst($t['bracket_type'] ?? 'Single elimination') }}</span>
                        @if(isset($t['start_date']))
                            <span class="t-meta-item">📅 {{ $t['start_date'] }}</span>
                        @endif
                    </div>
                </div>
                <div class="t-right">
                    <span class="t-status status-{{ $status }}">
                        {{ $status === 'open' ? 'Open' : ($status === 'active' ? 'In progress' : ($status === 'full' ? 'Full' : 'Finished')) }}
                    </span>
                    <span class="t-chevron">⌄</span>
                </div>
            </div>

            {{-- Participants progress bar --}}
            <div class="participants-wrap">
                <div class="participants-label">
                    <span>Registrations</span>
                    <span>{{ $filled }} / {{ $max }}</span>
                </div>
                <div class="participants-track">
                    <div class="participants-fill" style="width:{{ $pct }}%"></div>
                </div>
            </div>

            {{-- Expanded detail --}}
            <div class="t-detail">
                <div class="t-detail-left">
                    <div class="detail-grid">
                        <div class="detail-stat">
                            <div class="detail-stat-label">Format</div>
                            <div class="detail-stat-val" style="font-size:14px;font-family:var(--body);font-weight:500">
                                {{ ucfirst($t['bracket_type'] ?? 'Single elim') }}
                            </div>
                        </div>
                        <div class="detail-stat">
                            <div class="detail-stat-label">Max trainers</div>
                            <div class="detail-stat-val">{{ $max }}</div>
                        </div>
                        <div class="detail-stat">
                            <div class="detail-stat-label">League</div>
                            <div class="detail-stat-val" style="font-size:14px;font-family:var(--body);font-weight:500">
                                {{ $t['league_name'] ?? '—' }}
                            </div>
                        </div>
                        <div class="detail-stat">
                            <div class="detail-stat-label">Spots left</div>
                            <div class="detail-stat-val" style="color:{{ ($max - $filled) > 0 ? 'var(--accent2)' : '#ff7c72' }}">
                                {{ max(0, $max - $filled) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="t-detail-right">
                    @if($status === 'open' && !$registered && ($max - $filled) > 0)
                        <div class="detail-label">Register with</div>
                        <div class="select-wrap">
                            <select class="team-select" id="team-select-{{ $t['id'] ?? $i }}">
                                <option value="">— pick a team —</option>
                                @foreach($teams ?? [] as $team)
                                    <option value="{{ $team['id'] }}">{{ $team['team_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <form method="POST" action="{{ route('tournaments.register', $t['id']) }}" style="margin-top:8px">
                            @csrf
                            <input type="hidden" name="tournament_id" value="{{ $t['id'] ?? $i }}">
                            <input type="hidden" name="team_id" id="team-id-{{ $t['id'] ?? $i }}">
                            <button type="submit" class="btn-register"
                                onclick="syncTeam({{ $t['id'] ?? $i }})">
                                Register
                            </button>
                        </form>
                    @elseif($registered)
                        <div class="btn-registered">✓ Registered</div>
                    @elseif($status === 'active')
                        <a href="#" class="btn-register" style="background:rgba(245,200,66,0.12);color:var(--accent2);border:1px solid rgba(245,200,66,0.25)">
                            View bracket →
                        </a>
                    @else
                        <div class="btn-disabled">
                            {{ $status === 'full' ? 'Tournament full' : 'Registration closed' }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

    @empty
        <div class="empty-state">
            <div class="empty-icon">🏟</div>
            <div class="empty-text">No tournaments yet</div>
            <div class="empty-sub">Check back when an admin creates one</div>
        </div>
    @endforelse

</div>

@push('scripts')
<script>
function toggleCard(card) {
    card.classList.toggle('expanded');
}

function syncTeam(id) {
    const sel = document.getElementById('team-select-' + id);
    const inp = document.getElementById('team-id-' + id);
    if (inp) inp.value = sel ? sel.value : '';
}

// ── Filter tabs ───────────────────────────────────────────
const cards = document.querySelectorAll('.t-card');

function updateCount() {
    const visible = document.querySelectorAll('.t-card:not(.hidden)').length;
    document.getElementById('t-count').textContent = visible + ' tournament' + (visible !== 1 ? 's' : '');
}

document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const f = tab.dataset.filter;
        cards.forEach(c => {
            const match = f === 'all' || c.dataset.status === f ||
                (f === 'active' && c.dataset.status === 'active');
            c.classList.toggle('hidden', !match);
        });
        updateCount();
    });
});

updateCount();
</script>
@endpush

@endsection