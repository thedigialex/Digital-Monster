<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'message',
        'type',
        'item_id',
        'location_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
