<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEquipment extends Model
{
    use HasFactory;
     protected $fillable = [
        'image',
        'name',
        'stat',
    ];

    public function userTrainingEquipments()
    {
        return $this->hasMany(UserTrainingEquipment::class);
    }
}
