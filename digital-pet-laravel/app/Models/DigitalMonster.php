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
        'requiredEvoPoints',
        'evolution_routes', 
    ];

    public function eggGroup()
    {
        return $this->belongsTo(EggGroup::class, 'eggId');
    }

    public function getTypes() {
        return ['Data', 'Virus', 'Vaccine'];
    }
    
    public function generateType() {
        $types = $this->getTypes();
        $randomType = $types[array_rand($types)];
        return $randomType;
    }

}

