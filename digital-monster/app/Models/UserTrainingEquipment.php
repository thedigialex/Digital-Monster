<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTrainingEquipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'training_equipment_id',
        'stat_increase',
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
