<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamPokemon extends Model
{
    protected $table      = 'TEAM_POKEMON';
    protected $primaryKey = 'team_pokemon_id';
    public    $timestamps = false;

    protected $fillable = [
        'team_id', 'pokemon_id', 'slot'
    ];
}