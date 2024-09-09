<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDigitalMonster extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'digital_monster_id', 'name', 'level', 'exp', 'strength',
        'agility', 'defense', 'mind', 'age', 'hunger',
        'exercise', 'clean', 'energy', 'maxEnergy', 'wins', 'losses',
        'trainings', 'maxTrainings', 'type', 'isMain'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function digitalMonster()
    {
        return $this->belongsTo(DigitalMonster::class);
    }
}
