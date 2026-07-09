<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;

class PokedexController extends Controller
{
    public function index()
    {
        $pokemon = Pokemon::orderBy('pokemon_id')->get()->map(function ($p) {
            return [
                'id'        => $p->pokemon_id,
                'name'      => $p->name,
                'type'      => $p->type1,
                'types'     => array_filter([$p->type1, $p->type2]),
                'hp'        => $p->hp,
                'attack'    => $p->attack,
                'defense'   => $p->defense,
                'speed'     => $p->speed,
                'sprite'    => $p->sprite_url ?? '?',
                'sprite_url'=> $p->sprite_url,
            ];
        })->toArray();

        return view('pokedex', compact('pokemon'));
    }
}