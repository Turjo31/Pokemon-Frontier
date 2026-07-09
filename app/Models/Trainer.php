<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Trainer extends Authenticatable
{
    protected $table      = 'TRAINER';
    protected $primaryKey = 'trainer_id';
    public    $timestamps = false;

    protected $fillable = [
        'username', 'email', 'password_hash', 'rank_points', 'joined_at'
    ];

    protected $hidden = [
        'password_hash'
    ];

    // Override getAuthPassword so Laravel Auth uses password_hash column
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // A trainer owns many teams
    public function teams()
    {
        return $this->hasMany(Team::class, 'trainer_id', 'trainer_id');
    }

    // A trainer has many registrations
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'trainer_id', 'trainer_id');
    }

    // A trainer has many rankings
    public function rankings()
    {
        return $this->hasMany(Ranking::class, 'trainer_id', 'trainer_id');
    }

    // A trainer has many match results
    public function matches()
    {
        return $this->hasMany(MatchResult::class, 'trainer1_id', 'trainer_id');
    }
}