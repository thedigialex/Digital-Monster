<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'type', 'max_quantity', 'effect', 'price', 'rarity', 'available', 'image'];
}
