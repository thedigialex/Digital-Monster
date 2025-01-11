<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEquipment extends Model
{
     protected $fillable = [
        'image',
        'name',
        'stat',
        'max_level',
        'upgrade_item_id',
    ];

    public function userTrainingEquipments()
    {
        return $this->hasMany(UserTrainingEquipment::class);
    }
}
