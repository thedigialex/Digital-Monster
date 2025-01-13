<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalMonster extends Model
{
    protected $fillable = [
        'name',
        'egg_group_id',
        'stage',
        'required_evo_points', 
        'sprite_image_0',
        'element_0',
        'sprite_image_1',
        'element_1',
        'sprite_image_2',
        'element_2',
    ];

    public function eggGroup()
    {
        return $this->belongsTo(EggGroup::class);
    }

    public function evolutionToRoutes()
    {
        return $this->hasMany(EvolutionRoute::class, 'evolves_from');
    }
}
