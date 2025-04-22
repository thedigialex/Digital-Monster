<?php

namespace App\Listeners;

use App\Models\Item;
use App\Models\Location;
use App\Models\UserLocation;
use App\Models\UserItem;
use App\Models\Equipment;
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

        $meatItem = Item::where('name', 'Meat')->where('type', 'Consumable')->first();
        UserItem::create([
            'user_id' => $user->id,
            'item_id' => $meatItem->id,
            'quantity' => 25,
        ]);

        $dataCrystalItem = Item::where('name', 'DataCrystal')->where('type', 'Material')->first();
        UserItem::create([
            'user_id' => $user->id,
            'item_id' => $dataCrystalItem->id,
            'quantity' => 10,
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

        $starterLocation = Location::where('name', 'Green Meadow')->first();
        $location = UserLocation::create([
            'user_id' => $user->id,
            'location_id' => $starterLocation->id,
            'steps' => 0,
        ]);
        $user->current_location_id = $location->id;
        
        $user->save();
    }
}
