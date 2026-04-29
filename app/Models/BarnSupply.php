<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarnSupply extends Model
{
    protected $fillable = [
        'barn_id',
        'category_id',
        'supply_img_path',     
        'supply_name',
        'stock',
        'reorder_level',
        'supply_status'
    ];

    public function barn()
    {
        return $this->belongsTo(Barn::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'supply_id');
    }
}