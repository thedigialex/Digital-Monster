<?php

namespace App\Listeners;

use App\Models\Item;
use App\Models\Equipment;
use App\Models\UserItem;
use App\Models\UserEquipment;
use App\Events\UserRegistered;

class SetUpUserAccount
{
    public function __construct()
    {
        //
    }

    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        $item = Item::where('name', 'default')->where('type', 'Background')->first();
        
        $background = UserItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'quantity' => 1,
        ]);

        $user->background_id = $background->id;
        $user->save();

        $equipments = Equipment::all();

        foreach ($equipments as $equipment) {
            UserEquipment::create([
                'user_id' => $user->id,
                'equipment_id' => $equipment->id,
                'level' => 1,
            ]);
        }
    }
}
