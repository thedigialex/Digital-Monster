<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
     protected $fillable = [
        'name',
        'stat',
        'image',
        'max_level',
        'upgrade_item_id',
    ];
}
