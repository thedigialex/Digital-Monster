<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DigitalMonster;
use App\Models\UserDigitalMonster;
use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class ApiPostDataController extends Controller
{
    public function createUserDigitalMonster(Request $request)
    {
        $user = $request->user();
        UserDigitalMonster::where('user_id', $user->id)->where('isMain', true)->update(['isMain' => false]);
        $digitalMonster = DigitalMonster::findOrFail($request->digital_monster_id);
        $userDigitalMonster = new UserDigitalMonster();
        $userDigitalMonster->user_id = $user->id;
        $userDigitalMonster->digital_monster_id = $digitalMonster->id;
        $types = ['Data', 'Virus', 'Vaccine'];
        $userDigitalMonster->name = $request->name;
        $userDigitalMonster->type = $types[array_rand($types)];
        $userDigitalMonster->isMain = 1;
        $userDigitalMonster->level = 1;
        $userDigitalMonster->exp = 0;
        $userDigitalMonster->strength = 0;
        $userDigitalMonster->agility = 0;
        $userDigitalMonster->defense = 0;
        $userDigitalMonster->mind = 0;
        $userDigitalMonster->hunger = 0;
        $userDigitalMonster->exercise = 0;
        $userDigitalMonster->clean = 0;
        $userDigitalMonster->energy = 5;
        $userDigitalMonster->maxEnergy = 5;
        $userDigitalMonster->wins = 0;
        $userDigitalMonster->losses = 0;
        $userDigitalMonster->trainings = 0;
        $userDigitalMonster->maxTrainings = 0;
        $userDigitalMonster->currentEvoPoints = 0;
        $userDigitalMonster->sleepStartedAt = null;
        $userDigitalMonster->save();

        $userDigitalMonster->digital_monster = $digitalMonster;
        return response()->json([
            'status' => true,
            'message' => 'User Digital Monster created successfully.',
            'userDigitalMonsters' => [$userDigitalMonster],
        ]);
    }

    public function updateUserDigitalMonster(Request $request) {
        Log::info('Received updateUserDigitalMonster request:', $request->all());

        $request->merge([
            'sleepStartedAt' => $request->input('sleepStartedAt') !== null 
                ? preg_replace('/\.\d+$/', '', $request->input('sleepStartedAt')) 
                : null,
        ]);        
        $validated = $request->validate([
            'id' => 'required|integer|exists:user_digital_monsters,id',
            'name' => 'sometimes|string|max:255',
            'level' => 'sometimes|integer|min:1',
            'exp' => 'sometimes|integer|min:0',
            'strength' => 'sometimes|integer|min:0',
            'agility' => 'sometimes|integer|min:0',
            'defense' => 'sometimes|integer|min:0',
            'mind' => 'sometimes|integer|min:0',
            'hunger' => 'sometimes|integer|min:0',
            'exercise' => 'sometimes|integer|min:0',
            'clean' => 'sometimes|integer|min:0',
            'energy' => 'sometimes|integer|min:0',
            'maxEnergy' => 'sometimes|integer|min:0',
            'wins' => 'sometimes|integer|min:0',
            'losses' => 'sometimes|integer|min:0',
            'trainings' => 'sometimes|integer|min:0',
            'maxTrainings' => 'sometimes|integer|min:0',
            'currentEvoPoints' => 'sometimes|integer|min:0',
            'sleepStartedAt' => 'sometimes|date_format:Y-m-d H:i:s|nullable',
        ]);

        $userDigitalMonster = UserDigitalMonster::find($validated['id']);

        if ($userDigitalMonster->user_id !== $request->user()->id) {
            $response = [
                'status' => false,
                'message' => 'Unauthorized action. This digital monster does not belong to you.',
            ];
            return response()->json($response, 403);
        }

        $userDigitalMonster->fill($validated);
        $userDigitalMonster->save();

        return response()->json([
            'status' => true,
            'message' => 'User Digital Monster saved successfully.',
        ]);
    }

    //Items
    public function getItems(Request $request)
    {
        $user = $request->user();
        $itemType = $request->type;
        $itemsQuery = Item::where('type', $itemType)->where('isAvailable', 1);
        if ($itemType !== 'Consumable' && $itemType !== 'Material') {
            $ownedItemIds = $user->inventories->pluck('item_id')->toArray();
            $itemsQuery->whereNotIn('id', $ownedItemIds);
        }
        $items = $itemsQuery->get();
    
        return response()->json([
            'status' => true,
            'message' => "Items of type '$itemType' fetched successfully.",
            'items' => $items,
        ]);
    }
    
    public function buyItem(Request $request)
    {
        $user = $request->user();
        $item = Item::findOrFail($request->id);
        $user->bits -= $item->price;
        $user->save();
        $inventoryItem = Inventory::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();
        if ($inventoryItem) {
            $inventoryItem->quantity += 1;
            $inventoryItem->save();
        } else {
            $inventoryItem = Inventory::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'quantity' => 1,
                'isEquipped' => 0,
            ]);
        }
        $inventoryItem = $inventoryItem->load('item');
        return response()->json([
            'status' => true,
            'message' => 'Item purchased successfully.',
            'inventoryItems' => [$inventoryItem],
        ]);
    }

    public function updateInventoryItem(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
        ]);

        $inventoryItem = Inventory::findOrFail($validated['inventory_id']);

        if ($inventoryItem->item->type == 'consumable') {
            $inventoryItem->quantity--;

            if ($inventoryItem->quantity == 0) {
                $inventoryItem->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Consumable item used and removed from inventory.',
                ]);
            } else {
                $inventoryItem->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Consumable item used, quantity decreased.',
                    'inventoryItem' => $inventoryItem,
                ]);
            }
        } else {
            $inventoryItem->isEquipped = true;
            Inventory::where('user_id', $user->id)
                ->whereHas('item', function ($query) use ($inventoryItem) {
                    $query->where('type', $inventoryItem->item->type);
                })
                ->where('isEquipped', true)
                ->where('id', '!=', $inventoryItem->id)
                ->update(['isEquipped' => false]);

            $inventoryItem->save();

            return response()->json([
                'status' => true,
                'message' => 'Item equipped successfully.',
                'inventoryItem' => $inventoryItem,
            ]);
        }
    }
}
