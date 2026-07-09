<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table      = 'REGISTRATION';
    protected $primaryKey = 'reg_id';
    public    $timestamps = false;

    protected $fillable = [
        'trainer_id', 'tournament_id', 'team_id', 'registered_at'
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'trainer_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id', 'tournament_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }
}