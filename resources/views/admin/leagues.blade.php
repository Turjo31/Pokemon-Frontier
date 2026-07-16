@extends('layouts.app')

@section('title', 'Admin — Leagues & Tournaments')

@push('styles')
    <style>
        /* ── Two column layout ── */
        .admin-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            align-items: start;
        }

        /* ── Section panel ── */
        .section-panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.4s ease both;
        }

        .section-panel:nth-child(2) {
            animation-delay: 0.06s;
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .section-title {
            font-family: var(--display);
            font-size: 20px;
            letter-spacing: 1px;
            color: var(--text);
            line-height: 1;
        }

        .btn-small-add {
            font-size: 11px;
            font-weight: 600;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 6px 12px;
            cursor: pointer;
            font-family: var(--body);
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn-small-add:hover {
            background: #cc2e22;
        }

        /* ── League list ── */
        .item-list {
            padding: 6px 0;
        }

        .item-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-row:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .item-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .item-info {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-meta {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        .status-badge {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 8px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .status-upcoming {
            background: rgba(255, 255, 255, 0.06);
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .status-active {
            background: rgba(245, 200, 66, 0.12);
            color: #f5d060;
            border: 1px solid rgba(245, 200, 66, 0.25);
        }

        .status-finished {
            background: rgba(255, 255, 255, 0.04);
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .status-open {
            background: rgba(82, 196, 90, 0.12);
            color: #7ee382;
            border: 1px solid rgba(82, 196, 90, 0.25);
        }

        .status-full {
            background: rgba(232, 55, 42, 0.1);
            color: #ff7c72;
            border: 1px solid rgba(232, 55, 42, 0.2);
        }

        .item-actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .btn-icon {
            background: none;
            border: 1px solid transparent;
            border-radius: 6px;
            color: var(--muted);
            cursor: pointer;
            font-size: 13px;
            padding: 4px 8px;
            font-family: var(--body);
            transition: all 0.15s;
            white-space: nowrap;
            font-size: 11px;
            font-weight: 500;
        }

        .btn-icon:hover {
            color: var(--accent2);
            border-color: rgba(245, 200, 66, 0.3);
        }

        .btn-icon.danger:hover {
            color: #ff7c72;
            border-color: rgba(232, 55, 42, 0.3);
        }

        /* ── Simulate button ── */
        .btn-simulate {
            font-size: 11px;
            font-weight: 600;
            background: rgba(245, 200, 66, 0.1);
            color: var(--accent2);
            border: 1px solid rgba(245, 200, 66, 0.25);
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            font-family: var(--body);
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-simulate:hover {
            background: rgba(245, 200, 66, 0.18);
        }

        /* ── Empty state ── */
        .empty-item {
            padding: 36px 20px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        .empty-item-icon {
            font-size: 28px;
            opacity: 0.3;
            margin-bottom: 8px;
        }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: var(--card);
            border: 1px solid var(--border-hi);
            border-radius: 20px;
            width: 100%;
            max-width: 460px;
            animation: fadeUp 0.25s ease both;
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-family: var(--display);
            font-size: 20px;
            letter-spacing: 1px;
            color: var(--text);
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--muted);
            font-size: 22px;
            cursor: pointer;
            line-height: 1;
            transition: color 0.15s;
            padding: 0;
        }

        .modal-close:hover {
            color: var(--text);
        }

        .modal-body {
            padding: 18px 20px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }

        .field:last-child {
            margin-bottom: 0;
        }

        .field label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
        }

        .field input,
        .field select {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: var(--body);
            font-size: 13px;
            padding: 9px 12px;
            outline: none;
            transition: border-color 0.2s;
            width: 100%;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(245, 200, 66, 0.4);
        }

        .field input::placeholder {
            color: var(--muted);
        }

        .field select {
            appearance: none;
            cursor: pointer;
        }

        .field select option {
            background: var(--card);
        }

        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .modal-foot {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            padding: 14px 20px;
            border-top: 1px solid var(--border);
        }

        .btn-cancel {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            font-family: var(--body);
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-cancel:hover {
            color: var(--text);
            border-color: var(--border-hi);
        }

        .btn-save-modal {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--body);
            cursor: pointer;
            transition: background 0.18s;
        }

        .btn-save-modal:hover {
            background: #cc2e22;
        }

        @media (max-width: 900px) {
            .admin-cols {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-header animate-in"
        style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div>
            <div class="page-title">Admin — <span style="color:var(--accent)">Leagues</span></div>
            <div class="page-sub">Manage leagues and tournaments · Trigger match simulation</div>
        </div>
        <div
            style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);background:rgba(232,55,42,0.08);border:1px solid rgba(232,55,42,0.2);border-radius:8px;padding:6px 14px">
            ⚙ Admin panel
        </div>
    </div>

    <div class="admin-cols">

        {{-- ── Leagues ── --}}
        <div class="section-panel">
            <div class="section-head">
                <div class="section-title">Leagues</div>
                <button class="btn-small-add" onclick="openModal('league-modal')">+ New league</button>
            </div>
            <div class="item-list">
                @forelse($leagues ?? [] as $league)
                    <div class="item-row">
                        <div class="item-icon">🏟</div>
                        <div class="item-info">
                            <div class="item-name">{{ $league['name'] }}</div>
                            <div class="item-meta">
                                {{ $league['start_date'] ?? '—' }} → {{ $league['end_date'] ?? '—' }}
                            </div>
                        </div>
                        <span class="status-badge status-{{ strtolower($league['status'] ?? 'upcoming') }}">
                            {{ ucfirst($league['status'] ?? 'upcoming') }}
                        </span>
                        <div class="item-actions">
                            <button class="btn-icon" onclick="openEditLeague({{ json_encode($league) }})">Edit</button>
                            <form method="POST" action="{{ route('admin.leagues.destroy', $league['league_id']) }}"
                                style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon danger"
                                    onclick="return confirm('Delete this league?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-item">
                        <div class="empty-item-icon">🏟</div>
                        No leagues yet — create the first one
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── Tournaments ── --}}
        <div class="section-panel">
            <div class="section-head">
                <div class="section-title">Tournaments</div>
                <button class="btn-small-add" onclick="openModal('tournament-modal')">+ New tournament</button>
            </div>
            <div class="item-list">
                @forelse($tournaments ?? [] as $t)
                    <div class="item-row">
                        <div class="item-icon">🏆</div>
                        <div class="item-info">
                            <div class="item-name">{{ $t['name'] }}</div>
                            <div class="item-meta">
                                {{ $t['league_name'] ?? '—' }} · {{ $t['registered'] ?? 0 }}/{{ $t['max_participants'] ?? 16 }}
                                trainers
                            </div>
                        </div>
                        <span class="status-badge status-{{ strtolower($t['status'] ?? 'open') }}">
                            {{ ucfirst($t['status'] ?? 'open') }}
                        </span>
                        <div class="item-actions">
                            @if(strtolower($t['status'] ?? '') === 'active')
                                <form method="POST" action="#" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="tournament_id" value="{{ $t['id'] }}">
                                    <button type="submit" class="btn-simulate">⚡ Simulate round</button>
                                </form>
                            @endif
                            <button class="btn-icon" onclick="openEditTournament({{ json_encode($t) }})">Edit</button>
                            <form method="POST" action="{{ route('admin.tournaments.destroy', $t['id']) }}"
                                style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon danger"
                                    onclick="return confirm('Delete this tournament?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-item">
                        <div class="empty-item-icon">🏆</div>
                        No tournaments yet — create one inside a league
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── Add League Modal ── --}}
    <div class="modal-overlay" id="league-modal">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-title" id="league-modal-title">New league</div>
                <button class="modal-close" onclick="closeModal('league-modal')">×</button>
            </div>
            <form method="POST" action="{{ route('admin.leagues.store') }}" id="league-form">
                @csrf
                <input type="hidden" name="_method" id="league-method" value="POST">
                <input type="hidden" name="league_id" id="league-id">
                <div class="modal-body">
                    <div class="field">
                        <label>League name</label>
                        <input type="text" name="name" id="league-name" placeholder="e.g. Kanto Regional Season 1" required>
                    </div>
                    <div class="two-col">
                        <div class="field">
                            <label>Start date</label>
                            <input type="date" name="start_date" id="league-start">
                        </div>
                        <div class="field">
                            <label>End date</label>
                            <input type="date" name="end_date" id="league-end">
                        </div>
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select name="status" id="league-status">
                            <option value="upcoming">Upcoming</option>
                            <option value="active">Active</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" onclick="closeModal('league-modal')">Cancel</button>
                    <button type="submit" class="btn-save-modal">Save league</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Add Tournament Modal ── --}}
    <div class="modal-overlay" id="tournament-modal">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-title" id="tournament-modal-title">New tournament</div>
                <button class="modal-close" onclick="closeModal('tournament-modal')">×</button>
            </div>
            <form method="POST" action="{{ route('admin.tournaments.store') }}" id="tournament-form">
                @csrf

                <input type="hidden" name="tournament_id" id="tournament-id">
                <div class="modal-body">
                    <div class="field">
                        <label>Tournament name</label>
                        <input type="text" name="name" id="tournament-name" placeholder="e.g. Kanto Championship" required>
                    </div>
                    <div class="field">
                        <label>League</label>
                        <select name="league_id" id="tournament-league">
                            <option value="">— select a league —</option>
                            @foreach($leagues ?? [] as $league)
                                <option value="{{ $league['league_id'] ?? $league['id'] }}">{{ $league['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="two-col">
                        <div class="field">
                            <label>Max participants</label>
                            <input type="number" name="max_participants" id="tournament-max" value="16" min="2" max="64">
                        </div>
                        <div class="field">
                            <label>Bracket type</label>
                            <select name="bracket_type" id="tournament-bracket">
                                <option value="single_elimination">Single elimination</option>
                                <option value="double_elimination">Double elimination</option>
                                <option value="round_robin">Round robin</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select name="status" id="tournament-status">
                            <option value="open">Open</option>
                            <option value="active">Active</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" onclick="closeModal('tournament-modal')">Cancel</button>
                    <button type="submit" class="btn-save-modal">Save tournament</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openModal(id) {
                document.getElementById(id).classList.add('open');
            }

            function closeModal(id) {
                document.getElementById(id).classList.remove('open');
            }

            function openEditLeague(l) {
                document.getElementById('league-modal-title').textContent = 'Edit league';
                document.getElementById('league-method').value = 'PUT';
                document.getElementById('league-id').value = l.league_id || l.id;
                document.getElementById('league-name').value = l.name || '';
                document.getElementById('league-start').value = l.start_date || '';
                document.getElementById('league-end').value = l.end_date || '';
                document.getElementById('league-status').value = (l.status || 'upcoming').toLowerCase();
                openModal('league-modal');
            }

            function openEditTournament(t) {
                document.getElementById('tournament-modal-title').textContent = 'Edit tournament';
                document.getElementById('tournament-method').value = 'PUT';
                document.getElementById('tournament-id').value = t.tournament_id || t.id;
                document.getElementById('tournament-name').value = t.name || '';
                document.getElementById('tournament-league').value = t.league_id || '';
                document.getElementById('tournament-max').value = t.max_participants || 16;
                document.getElementById('tournament-bracket').value = t.bracket_type || 'single_elimination';
                document.getElementById('tournament-status').value = (t.status || 'open').toLowerCase();
                openModal('tournament-modal');
            }

            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target === this) this.classList.remove('open');
                });
            });
        </script>
    @endpush

@endsection