<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table      = 'POKEMON';
    protected $primaryKey = 'pokemon_id';
    public    $timestamps = false;

    protected $fillable = [
        'name', 'type1', 'type2', 'hp', 'attack', 'defense', 'speed', 'sprite_url'
    ];

    // A Pokemon can belong to many teams through TEAM_POKEMON
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'TEAM_POKEMON', 'pokemon_id', 'team_id')
                    ->withPivot('slot');
    }
}