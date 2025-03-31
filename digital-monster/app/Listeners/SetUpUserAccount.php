<?php

namespace App\Listeners;

use App\Models\Item;
use App\Models\Equipment;
use App\Models\UserItem;
use App\Models\UserEquipment;
use App\Events\UserRegistered;

class SetUpUserAccount
{
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;
        $user->bits = 50;
        $backgroundItem = Item::where('name', 'Default')->where('type', 'Background')->first();
        $background = UserItem::create([
            'user_id' => $user->id,
            'item_id' => $backgroundItem->id,
            'quantity' => 1,
        ]);

        $attackItem = Item::where('name', 'Bubble')->where('type', 'Attack')->first();
        UserItem::create([
            'user_id' => $user->id,
            'item_id' => $attackItem->id,
            'quantity' => 1,
        ]);

        $equipments = Equipment::all();

        foreach ($equipments as $equipment) {
            UserEquipment::create([
                'user_id' => $user->id,
                'equipment_id' => $equipment->id,
                'level' => 1,
            ]);
        }

        $user->background_id = $background->id;
        $user->save();
    }
}
