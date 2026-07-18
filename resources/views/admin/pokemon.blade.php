@extends('layouts.app')

@section('title', 'Admin — Pokédex Management')

@push('styles')
<style>
    /* ── Toolbar ── */
    .admin-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .admin-search {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 340px;
    }

    .admin-search-icon {
        position: absolute; left: 11px; top: 50%;
        transform: translateY(-50%);
        color: var(--muted); font-size: 14px; pointer-events: none;
    }

    .admin-search input {
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

    .admin-search input:focus { border-color: rgba(245,200,66,0.4); }
    .admin-search input::placeholder { color: var(--muted); }

    .btn-add {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--accent); color: #fff;
        border: none; border-radius: 10px;
        padding: 9px 18px; font-size: 13px; font-weight: 600;
        font-family: var(--body); cursor: pointer;
        transition: background 0.18s, transform 0.12s;
        text-decoration: none; white-space: nowrap;
    }

    .btn-add:hover { background: #cc2e22; transform: translateY(-1px); }

    .results-count { font-size: 12px; color: var(--muted); margin-left: auto; }

    /* ── Table ── */
    .admin-table-wrap {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        animation: fadeUp 0.4s ease both;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .admin-table thead tr {
        border-bottom: 1px solid var(--border);
    }

    .admin-table th {
        padding: 11px 16px;
        text-align: left;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        white-space: nowrap;
    }

    .admin-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    .admin-table tbody tr:last-child { border-bottom: none; }
    .admin-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .admin-table tbody tr.hidden { display: none; }

    .admin-table td {
        padding: 10px 16px;
        color: var(--text);
        vertical-align: middle;
    }

    .td-id {
        font-family: 'Courier New', monospace;
        font-size: 11px;
        color: var(--muted);
    }

    .td-name { font-weight: 500; }

    .type-badge {
        font-size: 9px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.4px;
        padding: 2px 7px; border-radius: 8px;
        display: inline-block; margin-right: 3px;
    }

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

    .stat-chip {
        font-family: 'Courier New', monospace;
        font-size: 11px;
        color: var(--muted);
    }

    .td-power {
        font-family: var(--display);
        font-size: 15px;
        letter-spacing: 1px;
        color: var(--accent2);
    }

    .td-actions { display: flex; gap: 6px; align-items: center; }

    .btn-edit {
        font-size: 11px; font-weight: 600;
        padding: 5px 12px; border-radius: 7px;
        border: 1px solid var(--border);
        background: transparent; color: var(--muted);
        cursor: pointer; font-family: var(--body);
        transition: all 0.15s; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
    }

    .btn-edit:hover { color: var(--accent2); border-color: rgba(245,200,66,0.35); }

    .btn-delete {
        font-size: 11px; font-weight: 600;
        padding: 5px 12px; border-radius: 7px;
        border: 1px solid transparent;
        background: transparent; color: var(--muted);
        cursor: pointer; font-family: var(--body);
        transition: all 0.15s;
        display: inline-flex; align-items: center; gap: 4px;
    }

    .btn-delete:hover { color: #ff7c72; border-color: rgba(232,55,42,0.3); background: rgba(232,55,42,0.06); }

    /* ── Modal ── */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 200;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .modal-overlay.open { display: flex; }

    .modal {
        background: var(--card);
        border: 1px solid var(--border-hi);
        border-radius: 20px;
        width: 100%;
        max-width: 520px;
        animation: fadeUp 0.25s ease both;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
    }

    .modal-title {
        font-family: var(--display);
        font-size: 22px; letter-spacing: 1px;
        color: var(--text);
    }

    .modal-close {
        background: none; border: none;
        color: var(--muted); font-size: 22px;
        cursor: pointer; line-height: 1;
        transition: color 0.15s; padding: 0;
    }

    .modal-close:hover { color: var(--text); }

    .modal-body { padding: 20px 22px; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-full { grid-column: span 2; }

    .field { display: flex; flex-direction: column; gap: 6px; }

    .field label {
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.8px;
        color: var(--muted);
    }

    .field input, .field select {
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: var(--body); font-size: 13px;
        padding: 9px 12px; outline: none;
        transition: border-color 0.2s;
        width: 100%;
    }

    .field input:focus, .field select:focus { border-color: rgba(245,200,66,0.4); }
    .field input::placeholder { color: var(--muted); }

    .field select { appearance: none; cursor: pointer; }
    .field select option { background: var(--card); }

    .modal-foot {
        display: flex; gap: 8px; justify-content: flex-end;
        padding: 16px 22px;
        border-top: 1px solid var(--border);
    }

    .btn-cancel {
        background: transparent; color: var(--muted);
        border: 1px solid var(--border); border-radius: 8px;
        padding: 9px 18px; font-size: 13px; font-weight: 500;
        font-family: var(--body); cursor: pointer;
        transition: all 0.15s;
    }

    .btn-cancel:hover { color: var(--text); border-color: var(--border-hi); }

    .btn-save-modal {
        background: var(--accent); color: #fff;
        border: none; border-radius: 8px;
        padding: 9px 22px; font-size: 13px; font-weight: 600;
        font-family: var(--body); cursor: pointer;
        transition: background 0.18s;
    }

    .btn-save-modal:hover { background: #cc2e22; }

    /* ── Delete confirm ── */
    .delete-modal .modal-body {
        text-align: center;
        padding: 28px 22px;
    }

    .delete-icon { font-size: 36px; margin-bottom: 12px; }
    .delete-text { font-size: 14px; color: var(--text); margin-bottom: 6px; }
    .delete-sub  { font-size: 12px; color: var(--muted); }

    /* ── Empty state ── */
    .empty-row td {
        text-align: center;
        padding: 48px;
        color: var(--muted);
        font-size: 13px;
    }
</style>
@endpush

@section('content')

<div class="page-header animate-in" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <div class="page-title">Admin — <span style="color:var(--accent)">Pokédex</span></div>
        <div class="page-sub">Add, edit, and remove Pokémon from the database</div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);background:rgba(232,55,42,0.08);border:1px solid rgba(232,55,42,0.2);border-radius:8px;padding:6px 14px">
        ⚙ Admin panel
    </div>
</div>

<div class="admin-toolbar animate-in" style="animation-delay:0.05s">
    <div class="admin-search">
        <span class="admin-search-icon">⌕</span>
        <input id="search" type="text" placeholder="Search by name or type...">
    </div>
    <button class="btn-add" onclick="openAddModal()">+ Add Pokémon</button>
    <span class="results-count" id="results-count"></span>
</div>

<div class="admin-table-wrap animate-in" style="animation-delay:0.1s">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Types</th>
                <th>HP</th>
                <th>ATK</th>
                <th>DEF</th>
                <th>SPD</th>
                <th>Power</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="pokemon-tbody">
            @forelse($pokemon as $p)
                @php
                    $types = array_map('strtolower', (array)($p['types'] ?? [$p['type1'] ?? 'normal']));
                    if (!empty($p['type2'])) $types[] = strtolower($p['type2']);
                    $types = array_unique(array_filter($types));
                    $total = ($p['hp'] ?? 0) + ($p['attack'] ?? 0) + ($p['defense'] ?? 0) + ($p['speed'] ?? 0);
                @endphp
                <tr data-name="{{ strtolower($p['name']) }}" data-types="{{ implode(',', $types) }}">
                    <td class="td-id">{{ $p['pokemon_id'] ?? $loop->iteration }}</td>
                    <td class="td-name">{{ $p['name'] }}</td>
                    <td>
                        @foreach($types as $t)
                            <span class="type-badge t-{{ $t }}">{{ ucfirst($t) }}</span>
                        @endforeach
                    </td>
                    <td class="stat-chip">{{ $p['hp'] ?? '—' }}</td>
                    <td class="stat-chip">{{ $p['attack'] ?? '—' }}</td>
                    <td class="stat-chip">{{ $p['defense'] ?? '—' }}</td>
                    <td class="stat-chip">{{ $p['speed'] ?? '—' }}</td>
                    <td class="td-power">{{ $total }}</td>
                    <td>
                        <div class="td-actions">
                            <button class="btn-edit" onclick="openEditModal({{ json_encode($p) }})">✎ Edit</button>
                            <button class="btn-delete" onclick="openDeleteModal({{ $p['pokemon_id'] ?? $loop->iteration }}, '{{ $p['name'] }}')">✕ Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="9">No Pokémon yet — add the first one!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add / Edit Modal --}}
<div class="modal-overlay" id="pokemon-modal">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-title" id="modal-title">Add Pokémon</div>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <form method="POST" id="pokemon-form" action="{{ route('admin.pokemon.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="pokemon_id" id="form-id">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="field form-full">
                        <label>Pokémon name</label>
                        <input type="text" name="name" id="form-name" placeholder="e.g. Charizard" required>
                    </div>
                    <div class="field">
                        <label>Primary type</label>
                        <select name="type1" id="form-type1">
                            @foreach(['normal','fire','water','grass','electric','ice','fighting','poison','ground','flying','psychic','bug','rock','ghost','dragon','dark','steel'] as $t)
                                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Secondary type (optional)</label>
                        <select name="type2" id="form-type2">
                            <option value="">— none —</option>
                            @foreach(['normal','fire','water','grass','electric','ice','fighting','poison','ground','flying','psychic','bug','rock','ghost','dragon','dark','steel'] as $t)
                                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>HP</label>
                        <input type="number" name="hp" id="form-hp" min="1" max="255" placeholder="e.g. 78" required>
                    </div>
                    <div class="field">
                        <label>Attack</label>
                        <input type="number" name="attack" id="form-attack" min="1" max="255" placeholder="e.g. 84" required>
                    </div>
                    <div class="field">
                        <label>Defense</label>
                        <input type="number" name="defense" id="form-defense" min="1" max="255" placeholder="e.g. 78" required>
                    </div>
                    <div class="field">
                        <label>Speed</label>
                        <input type="number" name="speed" id="form-speed" min="1" max="255" placeholder="e.g. 100" required>
                    </div>
                    <div class="field form-full">
                        <label>Sprite URL (optional)</label>
                        <input type="text" name="sprite_url" id="form-sprite" placeholder="https://...">
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save-modal" id="modal-save-btn">Save Pokémon</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete confirm modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal delete-modal" style="max-width:380px">
        <div class="modal-head">
            <div class="modal-title">Confirm delete</div>
            <button class="modal-close" onclick="closeDeleteModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="delete-icon">⚠</div>
            <div class="delete-text">Delete <strong id="delete-name"></strong>?</div>
            <div class="delete-sub">This will remove the Pokémon from the database and any teams it belongs to.</div>
        </div>
        <div class="modal-foot">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <form method="POST" id="delete-form" action="#">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" style="background:#e8372a;color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:600;font-family:var(--body);cursor:pointer">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Search ────────────────────────────────────────────────
const rows = document.querySelectorAll('#pokemon-tbody tr[data-name]');

function updateCount() {
    const visible = document.querySelectorAll('#pokemon-tbody tr[data-name]:not(.hidden)').length;
    document.getElementById('results-count').textContent = visible + ' Pokémon';
}

document.getElementById('search').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    rows.forEach(r => {
        const match = !q || r.dataset.name.includes(q) || r.dataset.types.includes(q);
        r.classList.toggle('hidden', !match);
    });
    updateCount();
});

updateCount();

// ── Add modal ─────────────────────────────────────────────
function openAddModal() {
    document.getElementById('modal-title').textContent = 'Add Pokémon';
    document.getElementById('modal-save-btn').textContent = 'Add Pokémon';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('pokemon-form').action = "{{ route('admin.pokemon.store') }}";
    document.getElementById('form-id').value = '';
    ['name','hp','attack','defense','speed','sprite'].forEach(f => {
        const el = document.getElementById('form-' + f);
        if (el) el.value = '';
    });
    document.getElementById('form-type1').value = 'normal';
    document.getElementById('form-type2').value = '';
    document.getElementById('pokemon-modal').classList.add('open');
}

// ── Edit modal ────────────────────────────────────────────
function openEditModal(p) {
    document.getElementById('modal-title').textContent = 'Edit Pokémon';
    document.getElementById('modal-save-btn').textContent = 'Save changes';
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('pokemon-form').action = `/admin/pokemon/${p.pokemon_id}`;
    document.getElementById('form-id').value    = p.pokemon_id;
    document.getElementById('form-name').value   = p.name || '';
    document.getElementById('form-type1').value  = (p.type1 || 'normal').toLowerCase();
    document.getElementById('form-type2').value  = (p.type2 || '').toLowerCase();
    document.getElementById('form-hp').value      = p.hp || '';
    document.getElementById('form-attack').value  = p.attack || '';
    document.getElementById('form-defense').value = p.defense || '';
    document.getElementById('form-speed').value   = p.speed || '';
    document.getElementById('form-sprite').value  = p.sprite_url || '';
    document.getElementById('pokemon-modal').classList.add('open');
}

function closeModal() {
    document.getElementById('pokemon-modal').classList.remove('open');
}

// ── Delete modal ──────────────────────────────────────────
function openDeleteModal(id, name) {
    document.getElementById('delete-name').textContent = name;
    document.getElementById('delete-form').action = `/admin/pokemon/${id}`;
    document.getElementById('delete-modal').classList.add('open');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('open');
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
});
</script>
@endpush

@endsection