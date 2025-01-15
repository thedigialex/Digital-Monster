<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\UserDigitalMonster;
use App\Models\DigitalMonster;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Item;


class ApiUserController extends Controller
{
    public function getEggs()
    {
        $eggMonsters = DigitalMonster::where('stage', 'egg')->get();
        return response()->json([
            'status' => true,
            'message' => 'Eggs',
            'digitalMonsters' => $eggMonsters,
        ]);
    }

    public function getUserDigitalMonsters(Request $request)
    {
        $user = $request->user();
        $userDigitalMonsters = UserDigitalMonster::where('user_id', $user->id)->get();
        return response()->json([
            'status' => true,
            'userDigitalMonsters' => $userDigitalMonsters,
        ]);
    }

    public function switchUserDigitalMonster(Request $request)
    {
        $validated = $request->validate([
            'user_digital_monster_id' => 'required|exists:user_digital_monsters,id',
        ]);
        $user = $request->user();
        $userDigitalMonster = UserDigitalMonster::findOrFail($validated['user_digital_monster_id']);
        if ($userDigitalMonster->isMain) {
            return response()->json([
                'status' => false,
                'message' => 'This digital monster is already set as main.',
            ]);
        }
        UserDigitalMonster::where('user_id', $user->id)->update(['isMain' => false]);
        $userDigitalMonster->isMain = true;
        $userDigitalMonster->save();
        return response()->json([
            'status' => true,
            'message' => 'User digital monster switched to main successfully.',
            'userDigitalMonster' => $userDigitalMonster,
        ]);
    }

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

    public function evolve(Request $request)
    {
        $user = $request->user();
        $userDigitalMonster = $user->getMainUserDigitalMonster()->evolve();
        if ($userDigitalMonster) {
            $userDigitalMonster->digitalMonster = DigitalMonster::find($userDigitalMonster->digital_monster_id);
            return response()->json([
                'status' => true,
                'message' => 'Monster evolved successfully.',
                'userDigitalMonster' => $userDigitalMonster,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Not enough evolution points to evolve this monster.',
            ], 400);
        }
    }

    public function saveUserDigitalMonster(Request $request)
    {
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
        $request->validate([
            'type' => 'required|string',
        ]);
    
        $user = $request->user();
        $itemType = $request->type;
    
        $items = Item::where('type', $itemType)->get();
    
        return response()->json([
            'status' => true,
            'message' => "Items of type '$itemType' fetched successfully.",
            'items' => $items,
        ]);
    }
    
    public function purchaseItem(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);
        $item = Item::findOrFail($validated['item_id']);
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
                'isEquipped' => false,
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Item purchased successfully.',
            'inventoryItem' => $inventoryItem,
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

    public function getFile($filename)
    {
        return response()->file(storage_path("app/private/{$filename}"));
    }
}
