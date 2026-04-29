<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'barn_id',
        'user_id',
        'supply_id',
        'supplier_id',
        'transaction_type',
        'quantity',
        'unit_cost',
        'remarks'
    ]; 

    public function supply()
    {
        return $this->belongsTo(BarnSupply::class, 'supply_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barn()
    {
        return $this->belongsTo(Barn::class, 'barn_id');
    }

    public function supplier()
    {
        return $this->belongsTo(BarnSupplier::class, 'supplier_id');
    }
} 