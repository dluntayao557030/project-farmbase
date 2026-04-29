<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarnSupplier extends Model
{
    protected $fillable = [
        'barn_id',
        'category_id',
        'supplier_name',
        'contact_number',
        'supplier_status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}