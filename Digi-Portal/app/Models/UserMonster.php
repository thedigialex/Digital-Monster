<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMonster extends Model
{
    protected $fillable = [
        'user_id',
        'monster_id',
        'main',
        'name',
        'type',
        'attack',
        'colosseum',
        'level',
        'exp',
        'strength',
        'agility',
        'defense',
        'mind',
        'hunger',
        'exercise',
        'clean',
        'energy',
        'max_energy',
        'wins',
        'losses',
        'trainings',
        'max_trainings',
        'evo_points',
        'sleep_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function monster()
    {
        return $this->belongsTo(Monster::class);
    }

    public function evolve()
    {
        $monster = $this->monster;
        $evolutions = Evolution::where('base_monster_id', $monster->id)->first();
        if ($evolutions) {
            if ($this->strength >= $this->mind) {
                $newMonsterId = $evolutions->route_0;
            } else {
                $newMonsterId = $evolutions->route_1 ?? $evolutions->route_0;
            }
            $this->monster_id = $newMonsterId;
            $this->evo_points = 0;
            $this->strength += $this->trainings / 5;
            $this->strength += $this->trainings / 5;
            $this->agility += $this->trainings / 5;
            $this->mind += $this->trainings / 5;
            $this->trainings = 0;
            $this->max_trainings = ($this->max_trainings + 15) * 2;
            $this->max_energy = $this->max_energy + 5;
            $this->energy = $this->max_energy;
            $this->save();
            return $this;
        }
        return null;
    }
}
