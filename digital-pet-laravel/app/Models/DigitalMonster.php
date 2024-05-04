<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DigitalMonster extends Model
{
    use HasFactory;

    protected $table = 'digital_monsters';

    protected $fillable = [
        'name', 'type', 'level', 'user_id', 'exp', 'strength', 'agility',
        'defense', 'mind', 'age', 'weight', 'min_weight', 'stage', 'hunger',
        'exercise', 'clean', 'energy', 'min_energy', 'wins', 'losses',
        'trainings', 'care_misses'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
