<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalMonster extends Model
{
    protected $fillable = [
        'eggId',
        'monsterId',
        'spriteSheet',
        'stage',
        'minWeight',
        'maxEnergy',
        'requiredEvoPoints'
    ];

    public function eggGroup()
    {
        return $this->belongsTo(EggGroup::class, 'eggId');
    }
}

