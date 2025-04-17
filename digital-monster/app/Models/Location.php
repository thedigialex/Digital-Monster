<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'unlock_location_id',
        'unlock_steps',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
