<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $table      = 'TOURNAMENT';
    protected $primaryKey = 'tournament_id';
    public    $timestamps = false;

    protected $fillable = [
        'league_id', 'name', 'max_participants', 'bracket_type', 'status'
    ];

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id', 'league_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'tournament_id', 'tournament_id');
    }

    public function matches()
    {
        return $this->hasMany(MatchResult::class, 'tournament_id', 'tournament_id');
    }
}