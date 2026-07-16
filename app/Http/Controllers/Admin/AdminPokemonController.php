<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use Illuminate\Http\Request;

class AdminPokemonController extends Controller
{
    public function index()
    {
        $pokemon = Pokemon::orderBy('pokemon_id')->get()->map(fn($p) => [
            'pokemon_id' => $p->pokemon_id,
            'name'       => $p->name,
            'type1'      => $p->type1,
            'type2'      => $p->type2,
            'hp'         => $p->hp,
            'attack'     => $p->attack,
            'defense'    => $p->defense,
            'speed'      => $p->speed,
            'sprite_url' => $p->sprite_url,
        ])->toArray();

        return view('admin.pokemon', compact('pokemon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:50',
            'type1'   => 'required|string|max:20',
            'hp'      => 'required|integer|min:1|max:255',
            'attack'  => 'required|integer|min:1|max:255',
            'defense' => 'required|integer|min:1|max:255',
            'speed'   => 'required|integer|min:1|max:255',
        ]);

        Pokemon::create([
            'name'       => $request->name,
            'type1'      => $request->type1,
            'type2'      => $request->type2 ?: null,
            'hp'         => $request->hp,
            'attack'     => $request->attack,
            'defense'    => $request->defense,
            'speed'      => $request->speed,
            'sprite_url' => $request->sprite_url ?: null,
        ]);

        return redirect()->route('admin.pokemon.index')
                         ->with('success', $request->name . ' added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:50',
            'type1'   => 'required|string|max:20',
            'hp'      => 'required|integer|min:1|max:255',
            'attack'  => 'required|integer|min:1|max:255',
            'defense' => 'required|integer|min:1|max:255',
            'speed'   => 'required|integer|min:1|max:255',
        ]);

        $pokemon = Pokemon::where('pokemon_id', $id)->firstOrFail();
        $pokemon->name       = $request->name;
        $pokemon->type1      = $request->type1;
        $pokemon->type2      = $request->type2 ?: null;
        $pokemon->hp         = $request->hp;
        $pokemon->attack     = $request->attack;
        $pokemon->defense    = $request->defense;
        $pokemon->speed      = $request->speed;
        $pokemon->sprite_url = $request->sprite_url ?: null;
        $pokemon->save();

        return redirect()->route('admin.pokemon.index')
                         ->with('success', $pokemon->name . ' updated successfully!');
    }

    public function destroy($id)
    {
        $pokemon = Pokemon::where('pokemon_id', $id)->firstOrFail();
        $pokemon->delete();

        return redirect()->route('admin.pokemon.index')
                         ->with('success', 'Pokémon deleted.');
    }
}