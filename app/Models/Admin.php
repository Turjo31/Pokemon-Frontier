<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table      = 'ADMIN';
    protected $primaryKey = 'admin_id';
    public    $timestamps = false;

    protected $fillable = [
        'username', 'password_hash'
    ];

    protected $hidden = [
        'password_hash'
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // An admin creates many leagues
    public function leagues()
    {
        return $this->hasMany(League::class, 'created_by', 'admin_id');
    }
}