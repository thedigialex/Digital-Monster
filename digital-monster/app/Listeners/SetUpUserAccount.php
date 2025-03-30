<?php

namespace App\Listeners;

use App\Models\Equipment;
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
