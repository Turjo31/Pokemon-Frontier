<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PokedexController extends Controller
{
    public function index()
    {
        $pokemon = [
            [
                'name' => 'Pikachu',
                'type' => 'Electric',
                'hp' => 35,
                'attack' => 55,
                'speed' => 90
            ],
            [
                'name' => 'Charizard',
                'type' => 'Fire/Flying',
                'hp' => 78,
                'attack' => 84,
                'speed' => 100
            ],
            [
                'name' => 'Bulbasaur',
                'type' => 'Grass/Poison',
                'hp' => 45,
                'attack' => 49,
                'speed' => 45
            ]
        ];

        return view('pokedex', compact('pokemon'));
    }
}