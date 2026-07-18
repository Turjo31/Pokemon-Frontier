@extends('layouts.app')

@section('title', 'Pokédex — Pokémon Frontier')

@push('styles')
<style>
    /* ── SEARCH + FILTERS ── */
    .dex-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 360px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 15px;
        pointer-events: none;
    }

    #search-input {
        padding-left: 36px;
    }

    .filter-pills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .filter-pill {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--muted);
        cursor: pointer;
        font-family: var(--body);
        transition: all 0.18s;
    }

    .filter-pill:hover { color: var(--text); border-color: var(--border-hi); }
    .filter-pill.active { background: rgba(245,200,66,0.1); border-color: rgba(245,200,66,0.4); color: var(--accent2); }

    .results-count {
        font-size: 12px;
        color: var(--muted);
        margin-left: auto;
        white-space: nowrap;
    }

    /* ── POKEMON GRID ── */
    .dex-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 14px;
    }

    /* ── POKEMON CARD ── */
    .poke-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px;
        cursor: pointer;
        transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
        animation: fadeUp 0.4s ease both;
    }

    .poke-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, color-mix(in srgb, var(--tc, transparent) 12%, transparent), transparent 60%);
        pointer-events: none;
        transition: opacity 0.3s;
        opacity: 0;
    }

    .poke-card:hover {
        transform: translateY(-4px);
        border-color: color-mix(in srgb, var(--tc, var(--border-hi)) 50%, var(--border-hi));
        box-shadow: 0 8px 24px color-mix(in srgb, var(--tc, #000) 15%, transparent);
    }

    .poke-card:hover::before { opacity: 1; }

    .poke-card.hidden { display: none; }

    .card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .poke-id {
        font-family: var(--display);
        font-size: 13px;
        color: var(--muted);
        letter-spacing: 1px;
    }

    .poke-types {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .poke-name {
        font-family: var(--display);
        font-size: 22px;
        letter-spacing: 1px;
        color: var(--text);
        margin-bottom: 12px;
        line-height: 1;
    }

    .poke-name span {
        color: var(--tc, var(--accent2));
    }

    /* ── STAT BARS IN CARD ── */
    .card-stats { margin-top: 14px; }

    /* ── TOTAL POWER CHIP ── */
    .power-chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
        padding: 8px 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border);
        border-radius: 8px;
    }

    .power-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
    }

    .power-val {
        font-family: var(--display);
        font-size: 18px;
        letter-spacing: 1px;
        color: var(--accent2);
    }

    /* ── CARD ACTIONS ── */
    .card-actions {
        margin-top: 12px;
        display: flex;
        gap: 6px;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity 0.2s, transform 0.2s;
    }

    .poke-card:hover .card-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .card-actions .btn {
        flex: 1;
        justify-content: center;
        padding: 7px 10px;
        font-size: 11px;
    }

    /* ── EMPTY STATE ── */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 64px 0;
        color: var(--muted);
    }

    .empty-state .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    .empty-state p { font-size: 15px; }
</style>
@endpush

@section('content')

<div class="page-header animate-in">
    <div class="page-title">Poké<span>dex</span></div>
    <div class="page-sub">{{ count($pokemon) }} Pokémon available · Select to add to your team</div>
</div>

<div class="dex-toolbar animate-in" style="animation-delay:0.05s">
    <div class="search-wrap">
        <span class="search-icon">⌕</span>
        <input
            id="search-input"
            class="input"
            type="text"
            placeholder="Search by name or type..."
            autocomplete="off"
        >
    </div>

    <div class="filter-pills">
        <button class="filter-pill active" data-type="all">All</button>
        <button class="filter-pill" data-type="fire">Fire</button>
        <button class="filter-pill" data-type="water">Water</button>
        <button class="filter-pill" data-type="grass">Grass</button>
        <button class="filter-pill" data-type="electric">Electric</button>
        <button class="filter-pill" data-type="psychic">Psychic</button>
        <button class="filter-pill" data-type="dragon">Dragon</button>
    </div>

    <span class="results-count" id="results-count"></span>
</div>

<div class="dex-grid" id="dex-grid">

    @forelse($pokemon as $p)
        @php
            $types   = array_map('strtolower', (array)($p['types'] ?? [$p['type'] ?? 'normal']));
            $primary = $types[0];
            $total   = ($p['hp'] ?? 0) + ($p['attack'] ?? 0) + ($p['defense'] ?? 0) + ($p['speed'] ?? 0);
            $id      = str_pad($p['id'] ?? ($loop->index + 1), 3, '0', STR_PAD_LEFT);
        @endphp

        <div
            class="poke-card type-{{ $primary }}"
            data-name="{{ strtolower($p['name']) }}"
            data-types="{{ implode(',', $types) }}"
            style="animation-delay: {{ $loop->index * 0.03 }}s"
        >
            <div class="card-top">
                <span class="poke-id">#{{ $id }}</span>
                <div class="poke-types">
                    @foreach($types as $t)
                        <span class="type-badge type-{{ $t }}">{{ ucfirst($t) }}</span>
                    @endforeach
                </div>
            </div>

            <div class="poke-name">{{ $p['name'] }}</div>

            <div class="card-stats">
                <div class="stat-row">
                    <span class="stat-label">HP</span>
                    <div class="stat-track">
                        <div class="stat-fill type-{{ $primary }}" style="width:{{ min(100, ($p['hp'] ?? 0) / 255 * 100) }}%"></div>
                    </div>
                    <span class="stat-num">{{ $p['hp'] ?? '—' }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">ATK</span>
                    <div class="stat-track">
                        <div class="stat-fill type-{{ $primary }}" style="width:{{ min(100, ($p['attack'] ?? 0) / 255 * 100) }}%"></div>
                    </div>
                    <span class="stat-num">{{ $p['attack'] ?? '—' }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">DEF</span>
                    <div class="stat-track">
                        <div class="stat-fill type-{{ $primary }}" style="width:{{ min(100, ($p['defense'] ?? 0) / 255 * 100) }}%"></div>
                    </div>
                    <span class="stat-num">{{ $p['defense'] ?? '—' }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">SPD</span>
                    <div class="stat-track">
                        <div class="stat-fill type-{{ $primary }}" style="width:{{ min(100, ($p['speed'] ?? 0) / 255 * 100) }}%"></div>
                    </div>
                    <span class="stat-num">{{ $p['speed'] ?? '—' }}</span>
                </div>
            </div>

            <div class="power-chip">
                <span class="power-label">Total power</span>
                <span class="power-val">{{ $total }}</span>
            </div>

        </div>

    @empty
        <div class="empty-state">
            <div class="empty-icon">◌</div>
            <p>No Pokémon found. Ask an admin to add some!</p>
        </div>
    @endforelse

</div>

@push('scripts')
<script>
    const cards   = document.querySelectorAll('.poke-card');
    const search  = document.getElementById('search-input');
    const pills   = document.querySelectorAll('.filter-pill');
    const counter = document.getElementById('results-count');
    let activeType = 'all';

    function updateCount() {
        const visible = document.querySelectorAll('.poke-card:not(.hidden)').length;
        counter.textContent = visible + ' Pokémon';
    }

    function filterCards() {
        const q = search.value.toLowerCase().trim();
        cards.forEach(card => {
            const name  = card.dataset.name;
            const types = card.dataset.types;
            const matchSearch = !q || name.includes(q) || types.includes(q);
            const matchType   = activeType === 'all' || types.includes(activeType);
            card.classList.toggle('hidden', !(matchSearch && matchType));
        });
        updateCount();
    }

    search.addEventListener('input', filterCards);

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            activeType = pill.dataset.type;
            filterCards();
        });
    });

    updateCount();

    /* Animate stat bars on load */
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.querySelectorAll('.stat-fill').forEach(bar => {
                    bar.style.width = bar.style.width;
                });
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(c => observer.observe(c));
</script>
@endpush

@endsection