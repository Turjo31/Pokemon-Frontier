<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    protected $table      = 'MATCH_RESULT';
    protected $primaryKey = 'match_id';
    public    $timestamps = false;

    protected $fillable = [
        'tournament_id', 'trainer1_id', 'trainer2_id',
        'team1_id', 'team2_id', 'winner_id',
        'team1_score', 'team2_score', 'match_date'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id', 'tournament_id');
    }

    public function trainer1()
    {
        return $this->belongsTo(Trainer::class, 'trainer1_id', 'trainer_id');
    }

    public function trainer2()
    {
        return $this->belongsTo(Trainer::class, 'trainer2_id', 'trainer_id');
    }

    public function winner()
    {
        return $this->belongsTo(Trainer::class, 'winner_id', 'trainer_id');
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id', 'team_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id', 'team_id');
    }
}