<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolutionRoute extends Model
{
    use HasFactory;

    protected $fillable = ['evolves_from', 'route_a', 'route_b'];

    public function fromMonster()
    {
        return $this->belongsTo(DigitalMonster::class, 'evolves_from');
    } 

    public function routeAMonster()
    {
        return $this->belongsTo(DigitalMonster::class, 'route_a');
    }

    public function routeBMonster()
    {
        return $this->belongsTo(DigitalMonster::class, 'route_b');
    }

    public function routesForMonster()
    {
        return $this->hasMany(EvolutionRoute::class, 'evolves_from');
    }
}
