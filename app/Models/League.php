<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $table      = 'LEAGUE';
    protected $primaryKey = 'league_id';
    public    $timestamps = false;

    protected $fillable = [
        'created_by', 'name', 'start_date', 'end_date', 'status'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'admin_id');
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'league_id', 'league_id');
    }

    public function rankings()
    {
        return $this->hasMany(Ranking::class, 'league_id', 'league_id');
    }
}