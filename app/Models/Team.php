<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'TEAM';
    protected $primaryKey = 'team_id';
    public $timestamps = false;

    protected $fillable = [
        'trainer_id',
        'team_name',
        'created_at'
    ];

    // A team belongs to a trainer
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'trainer_id');
    }

    // A team has many Pokémon through TEAM_POKEMON
    public function pokemon()
    {
        return $this->belongsToMany(Pokemon::class, 'TEAM_POKEMON', 'team_id', 'pokemon_id')
            ->withPivot('slot')
            ->orderBy('slot');
    }

    // A team has many registrations
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'team_id', 'team_id');
    }

    // Calculate total power of the team
    public function getTotalPowerAttribute()
    {
        return $this->pokemon->reduce(function ($carry, $p) {
            return $carry + $p->hp + $p->attack + $p->defense + $p->speed;
        }, 0);
    }
}