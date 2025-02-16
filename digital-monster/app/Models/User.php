<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Item;

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

    public function digitalMonsters()
    {
        return $this->hasMany(UserDigitalMonster::class);
    }

    public function getMainUserDigitalMonster()
    {
        return $this->digitalMonsters()->where('isMain', 1)->first();
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function trainingEquipments()
    {
        return $this->hasMany(UserTrainingEquipment::class);
    }

    protected static function boot()
    {
        parent::boot();

        //static::created(function ($user) {
        //    $basicTrainingEquipments = TrainingEquipment::whereIn('name', ['Strength', 'Agility', 'Defense', 'Mind', 'Clean'])->get();
//
        //    foreach ($basicTrainingEquipments as $equipment) {
        //        $user->trainingEquipments()->create([
        //            'training_equipment_id' => $equipment->id,
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
