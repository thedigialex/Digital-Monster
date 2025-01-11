<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTrainingEquipment extends Model
{
    protected $fillable = [
        'user_id',
        'training_equipment_id',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingEquipment()
    {
        return $this->belongsTo(TrainingEquipment::class);
    }
}
