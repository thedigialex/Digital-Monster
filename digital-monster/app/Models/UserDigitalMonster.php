<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDigitalMonster extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'digital_monster_id',
        'isMain',
        'name',
        'type',
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
        'maxEnergy',
        'wins',
        'losses',
        'trainings',
        'maxTrainings',
        'currentEvoPoints',
        'sleepStartedAt', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function digitalMonster()
    {
        return $this->belongsTo(DigitalMonster::class);
    }

    public function evolve()
    {
        $digitalMonster = $this->digitalMonster;
        if ($this->currentEvoPoints >= $digitalMonster->required_evo_points) {
            $evolutionRoutes = EvolutionRoute::where('evolves_from', $digitalMonster->id)->first();
            if ($evolutionRoutes) {
                if ($this->strength >= $this->mind) {
                    $newMonsterId = $evolutionRoutes->route_a;
                } else {
                    $newMonsterId = $evolutionRoutes->route_b ?? $evolutionRoutes->route_a; 
                }
                $this->digital_monster_id = $newMonsterId;
                $this->currentEvoPoints = 0;
                $this->strength += $this->trainings / 4;
                $this->strength += $this->trainings / 4;
                $this->agility += $this->trainings / 4;
                $this->mind += $this->trainings / 4;
                $this->trainings = 0;
                $this->maxTrainings = ( $this->maxTrainings + 15 ) * 2;
                $this->maxEnergy = $this->maxEnergy + 5;
                $this->energy = $this->maxEnergy;
                $this->save();
                return $this;
            }
        }
        return null;
    }
}
