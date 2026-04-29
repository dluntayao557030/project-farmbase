<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'username', 
        'password', 
        'user_type'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function barnStaff()
    {
        return $this->hasOne(BarnStaff::class, 'user_id');
    }

    public function barnsAsOwner()
    {
        return $this->hasMany(Barn::class, 'barn_owner_id');
    }

    // Helper accessor
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}