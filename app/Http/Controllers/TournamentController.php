<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\League;
use App\Models\Registration;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function index()
    {
        $trainer = Auth::guard('trainer')->user();

        $leagues = League::orderBy('league_id', 'desc')->get();

        $tournaments = Tournament::with('league')
            ->orderBy('tournament_id', 'desc')
            ->get()
            ->map(function ($t) use ($trainer) {
                $registered = Registration::where('trainer_id', $trainer->trainer_id)
                    ->where('tournament_id', $t->tournament_id)
                    ->exists();
                return [
                    'id'               => $t->tournament_id,
                    'name'             => $t->name,
                    'league_name'      => $t->league->name ?? '—',
                    'league_id'        => $t->league_id,
                    'max_participants' => $t->max_participants,
                    'bracket_type'     => $t->bracket_type,
                    'status'           => $t->status,
                    'registered'       => Registration::where('tournament_id', $t->tournament_id)->count(),
                    'is_registered'    => $registered,
                ];
            })->toArray();

        $teams = Team::where('trainer_id', $trainer->trainer_id)
            ->get()
            ->map(fn($t) => ['id' => $t->team_id, 'team_name' => $t->team_name])
            ->toArray();

        return view('tournaments', compact('tournaments', 'teams', 'leagues'));
    }

    public function register(Request $request, $id)
    {
        $request->validate(['team_id' => 'required|integer']);

        $trainer    = Auth::guard('trainer')->user();
        $tournament = Tournament::findOrFail($id);

        $exists = Registration::where('trainer_id', $trainer->trainer_id)
            ->where('tournament_id', $id)->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Already registered for this tournament.']);
        }

        $count = Registration::where('tournament_id', $id)->count();
        if ($count >= $tournament->max_participants) {
            return back()->withErrors(['error' => 'Tournament is full.']);
        }

        Registration::create([
            'trainer_id'    => $trainer->trainer_id,
            'tournament_id' => $id,
            'team_id'       => $request->team_id,
        ]);

        return back()->with('success', 'Registered for ' . $tournament->name . '!');
    }
}