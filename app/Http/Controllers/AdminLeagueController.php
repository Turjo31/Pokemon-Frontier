<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Tournament;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminLeagueController extends Controller
{
    public function index()
    {
        $leagues = League::orderBy('league_id', 'desc')->get()->map(fn($l) => [
            'league_id'  => $l->league_id,
            'name'       => $l->name,
            'start_date' => $l->start_date,
            'end_date'   => $l->end_date,
            'status'     => $l->status,
        ])->toArray();

        $tournaments = Tournament::with('league')
            ->orderBy('tournament_id', 'desc')
            ->get()->map(fn($t) => [
                'id'               => $t->tournament_id,
                'tournament_id'    => $t->tournament_id,
                'name'             => $t->name,
                'league_id'        => $t->league_id,
                'league_name'      => $t->league->name ?? '—',
                'max_participants' => $t->max_participants,
                'bracket_type'     => $t->bracket_type,
                'status'           => $t->status,
                'registered'       => Registration::where('tournament_id', $t->tournament_id)->count(),
            ])->toArray();

        return view('admin.leagues', compact('leagues', 'tournaments'));
    }

    public function storeLeague(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'status'     => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();

        League::create([
            'created_by' => $admin->admin_id,
            'name'       => $request->name,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.leagues.index')
                         ->with('success', 'League created!');
    }

    public function updateLeague(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'status' => 'required|string',
        ]);

        $league = League::where('league_id', $id)->firstOrFail();
        $league->name       = $request->name;
        $league->start_date = $request->start_date;
        $league->end_date   = $request->end_date;
        $league->status     = $request->status;
        $league->save();

        return redirect()->route('admin.leagues.index')
                         ->with('success', 'League updated!');
    }

    public function destroyLeague($id)
    {
        League::where('league_id', $id)->firstOrFail()->delete();
        return redirect()->route('admin.leagues.index')->with('success', 'League deleted.');
    }

    public function storeTournament(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'league_id'        => 'required|integer',
            'max_participants' => 'required|integer|min:2|max:64',
            'bracket_type'     => 'required|string',
            'status'           => 'required|string',
        ]);

        Tournament::create([
            'league_id'        => $request->league_id,
            'name'             => $request->name,
            'max_participants' => $request->max_participants,
            'bracket_type'     => $request->bracket_type,
            'status'           => $request->status,
        ]);

        return redirect()->route('admin.leagues.index')
                         ->with('success', 'Tournament created!');
    }

    public function updateTournament(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'status' => 'required|string',
        ]);

        $tournament = Tournament::where('tournament_id', $id)->firstOrFail();
        $tournament->name             = $request->name;
        $tournament->league_id        = $request->league_id;
        $tournament->max_participants = $request->max_participants;
        $tournament->bracket_type     = $request->bracket_type;
        $tournament->status           = $request->status;
        $tournament->save();

        return redirect()->route('admin.leagues.index')
                         ->with('success', 'Tournament updated!');
    }

    public function destroyTournament($id)
    {
        Tournament::where('tournament_id', $id)->firstOrFail()->delete();
        return redirect()->route('admin.leagues.index')->with('success', 'Tournament deleted.');
    }

    public function simulate($id)
    {
        DB::statement('BEGIN run_tournament_round(:tid); END;', ['tid' => $id]);
        return redirect()->route('admin.leagues.index')
                         ->with('success', 'Round simulated!');
    }
}