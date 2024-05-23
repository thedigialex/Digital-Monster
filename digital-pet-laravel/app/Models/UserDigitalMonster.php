<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDigitalMonster extends Model
{
    use HasFactory;

    protected $table = 'users_digital_monsters';

    protected $fillable = [
        'user_id', 'digital_monster_id',  'name', 'level', 'exp', 'strength', 
        'agility', 'defense', 'mind', 'age', 'weight', 'hunger',
        'exercise', 'clean', 'energy', 'wins', 'losses',
        'trainings', 'care_misses'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
