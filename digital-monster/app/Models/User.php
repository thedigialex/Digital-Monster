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

    protected static function boot()
    {
        parent::boot();

        //static::created(function ($user) {
        //    $basicTrainingEquipments = TrainingEquipment::whereIn('name', ['Strength', 'Agility', 'Defense', 'Mind', 'Clean'])->get();
//
        //    foreach ($basicTrainingEquipments as $equipment) {
        //        $user->trainingEquipments()->create([
        //            'equipment_id' => $equipment->id,
        //            'stat_increase' => 3,
        //            'level' => 1,
        //        ]);
        //    }
//
        //    $itemsWithZeroRarity = Item::where('rarity', 0)->get();
        //    foreach ($itemsWithZeroRarity as $item) {
        //        if ($item->type === 'consumable') {
        //            $user->inventories()->create([
        //                'item_id' => $item->id,
        //                'quantity' => 10,
        //            ]);
        //        } else {
        //            $user->inventories()->create([
        //                'item_id' => $item->id,
        //                'quantity' => 1,
        //                'isEquipped' => true,
        //            ]);
        //        }
        //    }
        //});
    }
}
