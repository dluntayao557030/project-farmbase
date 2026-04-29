<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barn extends Model
{
    protected $fillable = [
        'barn_owner_id',
        'barn_name',
        'country',
        'city',
        'region',
        'farm_type',
        'permit_number',
        'permit_doc_path'
    ];

    public function barnStaff()
    {
        return $this->hasMany(BarnStaff::class);
    }
} 