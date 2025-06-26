<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price',
        'image',
        'rarity',
        'effect',
        'available',
        'max_quantity',
    ];
}
