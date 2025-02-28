<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEquipment extends Model
{
    protected $fillable = [
        'level',
        'user_id',
        'equipment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
