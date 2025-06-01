<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $fillable = [
        'name',
        'egg_group_id',
        'stage',
        'evo_requirement', 
        'image_0',
        'element_0',
        'image_1',
        'element_1',
        'image_2',
        'element_2',
    ];

    public function eggGroup()
    {
        return $this->belongsTo(EggGroup::class);
    }

    public function evolution()
    {
        return $this->hasOne(Evolution::class, 'base_monster_id');
    }
}
