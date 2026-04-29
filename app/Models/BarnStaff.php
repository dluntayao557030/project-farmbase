<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarnStaff extends Model
{
    protected $fillable = [
        'user_id',
        'barn_id',
        'staff_status'
    ];

    public function barn()
    {
        return $this->belongsTo(Barn::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}