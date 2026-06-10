@extends('layouts.app')

@section('title', 'Team Builder — Pokémon Frontier')

@push('styles')
<style>
    .builder-wrap {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 16px;
        align-items: start;
    }

    .picker-toolbar {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
        flex-wrap: wrap;
        align-items: center;
    }

    .picker-search { position: relative; flex: 1; min-width: 160px; }

    .picker-search-icon {
        position: absolute; left: 11px; top: 50%;
        transform: translateY(-50%);
        color: var(--muted); font-size: 14px; pointer-events: none;
    }

    .picker-search input {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-family: var(--body);
        font-size: 13px;
        padding: 8px 12px 8px 32px;
        outline: none;
        transition: border-color 0.2s;
    }

    .picker-search input:focus { border-color: rgba(245,200,66,0.4); }
    .picker-search input::placeholder { color: var(--muted); }

    .type-pill {
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.5px;
        padding: 5px 11px; border-radius: 20px;
        border: 1px solid var(--border);
        background: transparent; color: var(--muted);
        cursor: pointer; font-family: var(--body);
        transition: all 0.15s; white-space: nowrap;
    }

    .type-pill:hover { color: var(--text); border-color: var(--border-hi); }
    .type-pill.active { background: rgba(245,200,66,0.1); border-color: rgba(245,200,66,0.35); color: var(--accent2); }

    .poke-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 8px;
    }

    .poke-tile {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px 10px;
        cursor: pointer;
        transition: border-color 0.18s, transform 0.15s;
        text-align: center;
        position: relative;
        animation: fadeUp 0.3s ease both;
        user-select: none;
    }

    .poke-tile:hover { border-color: var(--border-hi); transform: translateY(-2px); }
    .poke-tile.selected { border-color: var(--accent2); background: rgba(245,200,66,0.06); }
    .poke-tile.full-disabled { opacity: 0.35; pointer-events: none; }
    .poke-tile.hidden { display: none; }

    .poke-check {
        position: absolute; top: 7px; right: 7px;
        width: 16px; height: 16px;
        background: var(--accent2); border-radius: 50%;
        display: none; align-items: center; justify-content: center;
        font-size: 9px; color: #111; font-weight: 700;
    }

    .poke-tile.selected .poke-check { display: flex; }

    .poke-sprite { font-size: 30px; line-height: 1; margin-bottom: 5px; display: block; }
    .poke-tile-name { font-size: 11px; font-weight: 600; color: var(--text); margin-bottom: 4px; }

    .poke-tile-types { display: flex; gap: 3px; justify-content: center; flex-wrap: wrap; }

    .type-badge { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; padding: 2px 6px; border-radius: 8px; }
    .t-fire     { background:rgba(255,94,46,0.18);  color:#ff7a55; }
    .t-water    { background:rgba(59,158,255,0.18); color:#64b3ff; }
    .t-grass    { background:rgba(82,196,90,0.18);  color:#7dce83; }
    .t-electric { background:rgba(247,201,72,0.18); color:#f7d555; }
    .t-psychic  { background:rgba(224,64,140,0.18); color:#e87bbf; }
    .t-dragon   { background:rgba(139,92,246,0.18); color:#b08cf7; }
    .t-poison   { background:rgba(176,108,208,0.18);color:#cc99e6; }
    .t-normal   { background:rgba(136,153,170,0.18);color:#9aaabb; }
    .t-ghost    { background:rgba(96,96,200,0.18);  color:#9999dd; }
    .t-ice      { background:rgba(122,212,232,0.18);color:#8ddae8; }
    .t-flying   { background:rgba(128,174,232,0.18);color:#99bfe0; }
    .t-bug      { background:rgba(144,192,64,0.18); color:#aad455; }
    .t-rock     { background:rgba(187,160,96,0.18); color:#ccaa66; }
    .t-ground   { background:rgba(212,168,85,0.18); color:#d4a855; }
    .t-fighting { background:rgba(224,80,64,0.18);  color:#e07766; }
    .t-steel    { background:rgba(154,176,192,0.18);color:#aabfcc; }
    .t-dark     { background:rgba(167,128,112,0.18);color:#b89988; }

    .poke-tile-power { font-size: 10px; color: var(--muted); margin-top: 5px; }

    /* ── Team panel ── */
    .team-panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: 74px;
    }

    .team-panel-head { padding: 16px 18px; border-bottom: 1px solid var(--border); }

    .team-panel-title {
        font-family: var(--display);
        font-size: 22px; letter-spacing: 1px;
        color: var(--text); line-height: 1;
    }

    .team-panel-sub { font-size: 11px; color: var(--muted); margin-top: 4px; }

    .team-name-wrap { padding: 14px 18px; border-bottom: 1px solid var(--border); }

    .team-name-label {
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.8px;
        color: var(--muted); margin-bottom: 7px;
    }

    .team-name-input {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: var(--body); font-size: 13px;
        padding: 9px 12px; outline: none;
        transition: border-color 0.2s;
    }

    .team-name-input:focus { border-color: rgba(245,200,66,0.4); }
    .team-name-input::placeholder { color: var(--muted); }

    .slots-wrap { padding: 14px 18px; border-bottom: 1px solid var(--border); }

    .slots-label {
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.8px;
        color: var(--muted); margin-bottom: 10px;
        display: flex; align-items: center; justify-content: space-between;
    }

    .slots-count { color: var(--accent2); }
    .slot-list { display: flex; flex-direction: column; gap: 6px; }

    .slot {
        display: flex; align-items: center; gap: 10px;
        background: rgba(255,255,255,0.02);
        border: 1px dashed rgba(255,255,255,0.08);
        border-radius: 10px; padding: 8px 12px; min-height: 44px;
    }

    .slot.filled { border-style: solid; border-color: var(--border); background: rgba(255,255,255,0.04); }

    .slot-sprite { font-size: 22px; flex-shrink: 0; }
    .slot-info { flex: 1; min-width: 0; }
    .slot-name { font-size: 12px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .slot-power { font-size: 10px; color: var(--muted); }

    .slot-remove {
        background: none; border: none; color: var(--muted);
        cursor: pointer; font-size: 18px; padding: 0 2px; line-height: 1;
        transition: color 0.15s; flex-shrink: 0;
    }

    .slot-remove:hover { color: #ff7c72; }
    .slot-empty-text { font-size: 11px; color: rgba(255,255,255,0.13); margin: auto; }

    .power-bar {
        padding: 12px 18px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }

    .power-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--muted); }
    .power-value { font-family: var(--display); font-size: 24px; letter-spacing: 1px; color: var(--accent2); }

    .team-actions { padding: 14px 18px; display: flex; flex-direction: column; gap: 8px; }

    .btn-save {
        width: 100%; background: var(--accent); color: #fff;
        border: none; border-radius: 10px; padding: 11px;
        font-size: 13px; font-weight: 600; font-family: var(--body);
        letter-spacing: 0.4px; cursor: pointer;
        transition: background 0.18s, transform 0.12s;
    }

    .btn-save:hover  { background: #cc2e22; transform: translateY(-1px); }
    .btn-save:active { transform: scale(0.98); }
    .btn-save:disabled { background: rgba(255,255,255,0.07); color: var(--muted); cursor: not-allowed; transform: none; }

    .btn-clear {
        width: 100%; background: transparent; color: var(--muted);
        border: 1px solid var(--border); border-radius: 10px; padding: 9px;
        font-size: 12px; font-weight: 500; font-family: var(--body);
        cursor: pointer; transition: all 0.15s;
    }

    .btn-clear:hover { color: #ff7c72; border-color: rgba(232,55,42,0.3); }

    .toast {
        position: fixed; bottom: 28px; left: 50%;
        transform: translateX(-50%) translateY(60px);
        background: var(--card); border: 1px solid var(--border-hi);
        border-radius: 12px; padding: 12px 20px;
        font-size: 13px; font-weight: 500; color: var(--text);
        z-index: 999; transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
        pointer-events: none; white-space: nowrap;
    }

    .toast.show    { transform: translateX(-50%) translateY(0); }
    .toast.success { border-color: rgba(82,196,90,0.4); color: #7ee382; }
    .toast.error   { border-color: rgba(232,55,42,0.4); color: #ff7c72; }

    @media (max-width: 900px) {
        .builder-wrap { grid-template-columns: 1fr; }
        .team-panel { position: static; }
    }
</style>
@endpush

@section('content')

<div class="page-header animate-in">
    <div class="page-title">Team <span style="color:var(--accent)">Builder</span></div>
    <div class="page-sub">Pick up to 6 Pokémon · Name your team · Save</div>
</div>

<div class="builder-wrap">

    {{-- Left: Pokédex picker --}}
    <div>
        <div class="picker-toolbar">
            <div class="picker-search">
                <span class="picker-search-icon">⌕</span>
                <input id="search" type="text" placeholder="Search Pokémon...">
            </div>
            <button class="type-pill active" data-type="all">All</button>
            <button class="type-pill" data-type="fire">Fire</button>
            <button class="type-pill" data-type="water">Water</button>
            <button class="type-pill" data-type="grass">Grass</button>
            <button class="type-pill" data-type="electric">Electric</button>
            <button class="type-pill" data-type="psychic">Psychic</button>
            <button class="type-pill" data-type="dragon">Dragon</button>
        </div>

        <div class="poke-grid" id="poke-grid">
            @forelse($pokemon as $i => $p)
                @php
                    $types   = array_map('strtolower', (array)($p['types'] ?? [$p['type'] ?? 'normal']));
                    $primary = $types[0];
                    $total   = ($p['hp'] ?? 0) + ($p['attack'] ?? 0) + ($p['defense'] ?? 0) + ($p['speed'] ?? 0);
                    $sprite  = $p['sprite'] ?? '?';
                @endphp
                <div class="poke-tile"
                    data-id="{{ $p['id'] ?? $i }}"
                    data-name="{{ $p['name'] }}"
                    data-sprite="{{ $sprite }}"
                    data-power="{{ $total }}"
                    data-types="{{ implode(',', $types) }}"
                    data-namelo="{{ strtolower($p['name']) }}"
                    style="animation-delay:{{ $i * 0.02 }}s"
                    onclick="togglePoke(this)"
                >
                    <div class="poke-check">✓</div>
                    <span class="poke-sprite">{{ $sprite }}</span>
                    <div class="poke-tile-name">{{ $p['name'] }}</div>
                    <div class="poke-tile-types">
                        @foreach($types as $t)
                            <span class="type-badge t-{{ $t }}">{{ ucfirst($t) }}</span>
                        @endforeach
                    </div>
                    <div class="poke-tile-power">PWR {{ $total }}</div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--muted);font-size:13px">
                    No Pokémon in the database yet.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right: Team panel --}}
    <div class="team-panel">
        <div class="team-panel-head">
            <div class="team-panel-title">Your team</div>
            <div class="team-panel-sub" id="slot-sub">0 of 6 Pokémon selected</div>
        </div>

        <div class="team-name-wrap">
            <div class="team-name-label">Team name</div>
            <input class="team-name-input" id="team-name" type="text" placeholder="e.g. Alpha Squad" maxlength="50">
        </div>

        <div class="slots-wrap">
            <div class="slots-label">
                <span>Slots</span>
                <span class="slots-count" id="slot-count">0 / 6</span>
            </div>
            <div class="slot-list" id="slot-list">
                @for($i = 0; $i < 6; $i++)
                    <div class="slot" id="slot-{{ $i }}">
                        <span class="slot-empty-text">Empty slot</span>
                    </div>
                @endfor
            </div>
        </div>

        <div class="power-bar">
            <span class="power-label">Total power</span>
            <span class="power-value" id="total-power">0</span>
        </div>

        <div class="team-actions">
            <form method="POST" action="#" id="team-form">
                @csrf
                <input type="hidden" name="team_name" id="input-team-name">
                <input type="hidden" name="pokemon_ids" id="input-pokemon-ids">
                <button type="submit" class="btn-save" id="btn-save" disabled>Save team</button>
            </form>
            <button class="btn-clear" onclick="clearTeam()">Clear all</button>
        </div>
    </div>

</div>

<div class="toast" id="toast"></div>

@push('scripts')
<script>
const MAX = 6;
let team = [];

function togglePoke(tile) {
    const id     = tile.dataset.id;
    const name   = tile.dataset.name;
    const sprite = tile.dataset.sprite;
    const power  = parseInt(tile.dataset.power);
    const idx    = team.findIndex(p => p.id === id);

    if (idx !== -1) {
        team.splice(idx, 1);
        tile.classList.remove('selected');
    } else {
        if (team.length >= MAX) {
            showToast('Team is full — remove a Pokémon first', 'error');
            return;
        }
        team.push({ id, name, sprite, power });
        tile.classList.add('selected');
    }

    renderSlots();
    updateDisabled();
    updateSaveBtn();
}

function renderSlots() {
    const list = document.getElementById('slot-list');
    list.innerHTML = '';

    for (let i = 0; i < MAX; i++) {
        const slot = document.createElement('div');
        slot.className = 'slot' + (team[i] ? ' filled' : '');

        if (team[i]) {
            const p = team[i];
            slot.innerHTML = `
                <span class="slot-sprite">${p.sprite}</span>
                <div class="slot-info">
                    <div class="slot-name">${p.name}</div>
                    <div class="slot-power">Power: ${p.power}</div>
                </div>
                <button class="slot-remove" onclick="removePoke('${p.id}')" title="Remove">×</button>
            `;
        } else {
            slot.innerHTML = `<span class="slot-empty-text">Empty slot</span>`;
        }
        list.appendChild(slot);
    }

    const count = team.length;
    document.getElementById('slot-count').textContent  = count + ' / ' + MAX;
    document.getElementById('slot-sub').textContent    = count + ' of ' + MAX + ' Pokémon selected';
    document.getElementById('total-power').textContent = team.reduce((s, p) => s + p.power, 0).toLocaleString();
    document.getElementById('input-pokemon-ids').value = team.map(p => p.id).join(',');
}

function removePoke(id) {
    team = team.filter(p => p.id !== id);
    document.querySelectorAll('.poke-tile').forEach(t => {
        if (t.dataset.id === id) t.classList.remove('selected');
    });
    renderSlots();
    updateDisabled();
    updateSaveBtn();
}

function updateDisabled() {
    const full = team.length >= MAX;
    document.querySelectorAll('.poke-tile:not(.selected)').forEach(t => {
        t.classList.toggle('full-disabled', full);
    });
}

function updateSaveBtn() {
    const name = document.getElementById('team-name').value.trim();
    document.getElementById('btn-save').disabled = team.length === 0 || name.length === 0;
}

function clearTeam() {
    team = [];
    document.querySelectorAll('.poke-tile').forEach(t => t.classList.remove('selected', 'full-disabled'));
    renderSlots();
    updateSaveBtn();
}

function showToast(msg, type = '') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className   = 'toast' + (type ? ' ' + type : '');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

document.getElementById('search').addEventListener('input', filterTiles);
document.getElementById('team-name').addEventListener('input', updateSaveBtn);

document.querySelectorAll('.type-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.type-pill').forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        activeType = pill.dataset.type;
        filterTiles();
    });
});

let activeType = 'all';

function filterTiles() {
    const q = document.getElementById('search').value.toLowerCase().trim();
    document.querySelectorAll('.poke-tile').forEach(t => {
        const matchSearch = !q || t.dataset.namelo.includes(q) || t.dataset.types.includes(q);
        const matchType   = activeType === 'all' || t.dataset.types.includes(activeType);
        t.classList.toggle('hidden', !(matchSearch && matchType));
    });
}

document.getElementById('team-form').addEventListener('submit', () => {
    document.getElementById('input-team-name').value = document.getElementById('team-name').value.trim();
});

renderSlots();
</script>
@endpush

@endsection