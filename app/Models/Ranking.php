<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $table      = 'RANKING';
    protected $primaryKey = 'ranking_id';
    public    $timestamps = false;

    protected $fillable = [
        'trainer_id', 'league_id', 'points', 'wins', 'losses', 'last_updated'
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'trainer_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id', 'league_id');
    }
}