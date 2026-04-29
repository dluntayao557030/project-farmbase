<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarnOwner extends Model
{
        protected $fillable = [
        'user_id',
        'account_status'
    ];
}
