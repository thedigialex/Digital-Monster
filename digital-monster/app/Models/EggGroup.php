<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggGroup extends Model
{

    protected $fillable = ['name', 'field_type'];

    public function digitalMonsters()
    {
        return $this->hasMany(DigitalMonster::class);
    }
}
