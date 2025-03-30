<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bits',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userMonsters()
    {
        return $this->hasMany(UserMonster::class);
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class);
    }

    public function userEquipment()
    {
        return $this->hasMany(UserEquipment::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
