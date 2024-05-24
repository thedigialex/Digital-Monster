<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DigitalMonster extends Model
{
    use HasFactory;

    protected $table = 'digital_monsters';

    protected $fillable = [
        'egg_id', 'monster_id',
        'sprite_sheet', 'stage',
        'type', 'min_weight', 'max_energy',
        'required_evo_points'
    ];
}
