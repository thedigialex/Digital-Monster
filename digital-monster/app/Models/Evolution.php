<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evolution extends Model
{

    protected $fillable = ['base_monster_id', 'route_0', 'route_1'];

    public function baseMonster()
    {
        return $this->belongsTo(Monster::class, 'base_monster_id');
    } 

    public function routeZero()
    {
        return $this->belongsTo(Monster::class, 'route_0');
    }

    public function routeOne()
    {
        return $this->belongsTo(Monster::class, 'route_1');
    }
}
