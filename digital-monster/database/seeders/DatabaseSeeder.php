<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Event;
use App\Models\Location;
use App\Models\Equipment;
use App\Events\UserRegistered;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Equipment::create([
            'icon' => 'fa-hard-drive',
            'type' => 'DigiGarden',
            'max_level' => 5,
        ]);

        Equipment::create([
            'icon' => 'fa-memory',
            'type' => 'DigiGate',
            'max_level' => 5,
        ]);

        Equipment::create([
            'icon' => 'fa-dumbbell',
            'type' => 'Stat',
            'stat' => 'Strength',
            'max_level' => 5,
        ]);

        Equipment::create([
            'icon' => 'fa-running',
            'type' => 'Stat',
            'stat' => 'Agility',
            'max_level' => 5,
        ]);

        Equipment::create([
            'icon' => 'fa-shield-alt',
            'type' => 'Stat',
            'stat' => 'Defense',
            'max_level' => 5,
        ]);

        Equipment::create([
            'icon' => 'fa-brain',
            'type' => 'Stat',
            'stat' => 'Mind',
            'max_level' => 5,
        ]);

        Item::create([
            'name' => 'Default',
            'price' => 0,
            'type' => 'Background',
            'rarity' => 'Common',
        ]);

        Item::create([
            'name' => 'Bubble',
            'price' => 0,
            'type' => 'Attack',
            'rarity' => 'Common',
        ]);

        Item::create([
            'name' => 'Meat',
            'price' => 5,
            'type' => 'Consumable',
            'rarity' => 'Common',
            'effect' => 'H,1-EVO,5',
            'max_quantity' => 99
        ]);

        Item::create([
            'name' => 'DataCrystal',
            'price' => 5000,
            'type' => 'Material',
            'rarity' => 'Legendary',
            'max_quantity' => 10
        ]);

        Item::create([
            'name' => 'DigiCore V1',
            'price' => 5000,
            'type' => 'Consumable',
            'rarity' => 'Uncommon',
            'effect' => 'STATS,10',
            'max_quantity' => 99
        ]);

        Item::create([
            'name' => 'DigiCore V2',
            'price' => 5000,
            'type' => 'Consumable',
            'rarity' => 'Rare',
            'effect' => 'STATS,20',
            'max_quantity' => 99
        ]);

        Item::create([
            'name' => 'DigiCore V3',
            'price' => 5000,
            'type' => 'Consumable',
            'rarity' => 'Legendary',
            'effect' => 'STATS,30',
            'max_quantity' => 99
        ]);

        $starterLocation = Location::create([
            'name' => 'Green Meadow',
            'description' => 'A calm, lush field where many adventures begin.',
            'unlock_location_id' => null,
            'unlock_steps' => 0,
        ]);

        $messages = [
            "sniffs the air curiously...",
            "found something interesting!",
            "stumbles upon a strange footprint...",
            "eagerly explores ahead!",
            "stops to listen carefully...",
            "lets out a cheerful cry as it moves forward!",
            "is scanning the area for any signs of adventure!",
            "seems excited about what lies ahead!",
            "picks up the pace, eager to discover more!",
        ];

        foreach ($messages as $message) {
            Event::create([
                'message' => $message,
                'type' => 0,
                'item_id' => null,
                'location_id' => $starterLocation->id,
            ]);
        }

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('ChangeMe1'),
            'role' => 'admin',
        ]);

        event(new UserRegistered($user));
    }
}
