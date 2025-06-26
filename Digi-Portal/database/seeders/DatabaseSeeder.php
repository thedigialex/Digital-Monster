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
        $equipments = [
            ['icon' => 'fa-hard-drive', 'type' => 'DigiGarden', 'max_level' => 5],
            ['icon' => 'fa-memory', 'type' => 'DigiGate', 'max_level' => 5],
            ['icon' => 'fa-dumbbell', 'type' => 'Stat', 'stat' => 'Strength', 'max_level' => 5],
            ['icon' => 'fa-running', 'type' => 'Stat', 'stat' => 'Agility', 'max_level' => 5],
            ['icon' => 'fa-shield-alt', 'type' => 'Stat', 'stat' => 'Defense', 'max_level' => 5],
            ['icon' => 'fa-brain', 'type' => 'Stat', 'stat' => 'Mind', 'max_level' => 5],
        ];

        foreach ($equipments as $equipment) {
            Equipment::firstOrCreate(
                ['type' => $equipment['type'], 'stat' => $equipment['stat'] ?? null],
                $equipment
            );
        }

        $items = [
            ['name' => 'Default', 'price' => 0, 'type' => 'Background', 'rarity' => 'Common'],
            ['name' => 'Bubble', 'price' => 0, 'type' => 'Attack', 'rarity' => 'Common'],
            ['name' => 'Meat', 'price' => 5, 'type' => 'Consumable', 'rarity' => 'Common', 'effect' => 'H,1-EVO,5', 'max_quantity' => 99],
            ['name' => 'DataCrystal', 'price' => 5000, 'type' => 'Material', 'rarity' => 'Legendary', 'max_quantity' => 10],
            ['name' => 'DigiCore V1', 'price' => 5000, 'type' => 'Consumable', 'rarity' => 'Uncommon', 'effect' => 'STATS,10', 'max_quantity' => 99, 'available' => 0],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }

        $starterLocation = Location::firstOrCreate(
            ['name' => 'Green Meadow'],
            [
                'description' => 'A calm, lush field where many adventures begin.',
                'unlock_location_id' => null,
                'unlock_steps' => 0,
            ]
        );

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
            Event::firstOrCreate([
                'message' => $message,
                'location_id' => $starterLocation->id,
                'type' => 0,
                'item_id' => null,
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('ChangeMe1'),
                'role' => 'admin',
            ]
        );
        
        if ($user->wasRecentlyCreated) {
            event(new UserRegistered($user));
        }
    }
}
