<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'type', 'effect', 'price', 'rarity', 'isAvailable', 'image'];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
