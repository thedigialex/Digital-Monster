<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggGroup extends Model
{

    protected $fillable = ['name', 'field'];

    public function monsters()
    {
        return $this->hasMany(Monster::class);
    }
}
