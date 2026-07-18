<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Pokemon;
use App\Models\TeamPokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\MatchResult;

class TeamController extends Controller
{
    // Show team builder with all pokemon
    public function index()
    {
        $trainer = Auth::guard('trainer')->user();

        $pokemon = Pokemon::orderBy('pokemon_id')->get()->map(function ($p) {
            return [
                'id' => $p->pokemon_id,
                'name' => $p->name,
                'type1' => $p->type1,
                'type2' => $p->type2,
                'types' => array_filter([$p->type1, $p->type2]),
                'hp' => $p->hp,
                'attack' => $p->attack,
                'defense' => $p->defense,
                'speed' => $p->speed,
                'sprite' => $p->sprite_url ?? '?',
            ];
        })->toArray();

        $teams = Team::where('trainer_id', $trainer->trainer_id)
            ->with('pokemon')
            ->get();

        return view('teams', compact('pokemon', 'teams'));
    }

    // Save a new team
    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:50',
            'pokemon_ids' => 'required|string',
        ]);

        $trainer = Auth::guard('trainer')->user();
        $pokemonIds = array_filter(explode(',', $request->pokemon_ids));

        if (count($pokemonIds) < 1 || count($pokemonIds) > 6) {
            return back()->withErrors(['pokemon_ids' => 'A team must have between 1 and 6 Pokémon.']);
        }

        // Create the team
        $team = Team::create([
            'trainer_id' => $trainer->trainer_id,
            'team_name' => $request->team_name,
        ]);

        // Add pokemon to team
        foreach ($pokemonIds as $slot => $pokemonId) {
            TeamPokemon::create([
                'team_id' => $team->team_id,
                'pokemon_id' => (int) $pokemonId,
                'slot' => $slot + 1,
            ]);
        }

        return redirect()->route('teams.index')
            ->with('success', 'Team "' . $request->team_name . '" created successfully!');
    }

    // Delete a team
    public function destroy($id)
    {
        $trainer = Auth::guard('trainer')->user();
        $team = Team::where('team_id', $id)
            ->where('trainer_id', $trainer->trainer_id)
            ->firstOrFail();

        // Remove pokemon slots first
        TeamPokemon::where('team_id', $team->team_id)->delete();
        Registration::where('team_id', $team->team_id)->delete();
        MatchResult::where('team1_id', $team->team_id)
            ->orWhere('team2_id', $team->team_id)
            ->delete();
        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted.');
    }
}