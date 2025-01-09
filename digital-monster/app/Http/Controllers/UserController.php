<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDigitalMonster;
use App\Models\DigitalMonster;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\TrainingEquipment;
use App\Models\UserTrainingEquipment;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    //User Digital Monster
    public function showUserDigitalMonsters(Request $request)
    {
        $user = User::with('digitalMonsters')->findOrFail($request->input('id'));
        return view('users.user_digital_monster_index', compact('user'));
    }

    public function editUserDigitalMonster(Request $request)
    {
        $allDigitalMonsters = DigitalMonster::with('eggGroup')->get();
        if ($allDigitalMonsters->isEmpty()) {
            return redirect()->route('digital_monsters.index')->with('error', 'No digital monsters found.');
        }
        $userDigitalMonster = UserDigitalMonster::find($request->input('id'));
        $user = User::findOrFail($request->input('userId'));
        return view('users.user_digital_monster_form', compact('user', 'userDigitalMonster', 'allDigitalMonsters'));
    }

    public function updateUserDigitalMonster(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'digital_monster_id' => 'required|exists:digital_monsters,id',
            'type' => 'required|in:Data,Virus,Vaccine',
            'level' => 'required|integer|min:1',
            'exp' => 'required|integer|min:0',
            'strength' => 'required|integer|min:0',
            'agility' => 'required|integer|min:0',
            'defense' => 'required|integer|min:0',
            'mind' => 'required|integer|min:0',
            'hunger' => 'required|integer|min:0',
            'exercise' => 'required|integer|min:0',
            'clean' => 'required|integer|min:0',
            'energy' => 'required|integer|min:0',
            'maxEnergy' => 'required|integer|min:0',
            'wins' => 'required|integer|min:0',
            'losses' => 'required|integer|min:0',
            'trainings' => 'required|integer|min:0',
            'maxTrainings' => 'required|integer|min:0',
            'currentEvoPoints' => 'required|integer|min:0',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($request->has('isMain')) {
            $validated['isMain'] = true;
            UserDigitalMonster::where('user_id', $validated['user_id'])
                ->where('id', '!=', $request->input('id'))
                ->update(['isMain' => false]);
        } else {
            $validated['isMain'] = false;
        }
        if ($request->has('id')) {
            $userDigitalMonster = UserDigitalMonster::findOrFail($request->input('id'));
            $userDigitalMonster->update($validated);
        } else {
            $userDigitalMonster = UserDigitalMonster::create($validated);
        }
        return redirect()->route('user.digital_monsters', ['id' => $validated['user_id']])
            ->with('success', 'Digital Monster saved successfully.');
    }

    public function destroyUserDigitalMonster($id)
    {
        $userDigitalMonster = UserDigitalMonster::findOrFail($id);
        $userDigitalMonster->delete();
        return redirect()->route('user.digital_monsters', ['id' => $userDigitalMonster->user_id])
            ->with('success', 'Digital Monster deleted successfully.');
    }

    //Inventory
    public function showUserInventory(Request $request)
    {
        $user = User::with('inventories.item')->findOrFail($request->input('id'));
        return view('users.user_inventory_index', compact('user'));
    }

    public function editUserInventory(Request $request)
    {
        $allItems = Item::all();
        $inventoryItem = Inventory::find($request->input('id'));
        $user = User::findOrFail($request->input('userId'));
        return view('users.user_inventory_form', compact('user', 'inventoryItem', 'allItems'));
    }

    public function updateUserInventory(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id'
        ]);

        $item = Item::findOrFail($request->input('item_id'));
        if ($request->has('isEquipped') && $request->isEquipped == true) {
            $validated['isEquipped'] = true;
            Inventory::where('user_id', $request->input('user_id'))
                ->whereHas('item', function ($query) use ($item) {
                    $query->where('type', $item->type);
                })
                ->where('isEquipped', true)
                ->update(['isEquipped' => false]);
        } else {
            $validated['isEquipped'] = false;
        }

        if ($request->has('id')) {
            $inventoryItem = Inventory::findOrFail($request->input('id'));
            $inventoryItem->update($validated);
        } else {
            Inventory::create($validated);
        }
        return redirect()->route('user.inventory', ['id' => $validated['user_id']])
            ->with('success', 'Inventory item saved successfully.');
    }

    public function destroyUserInventory($id)
    {
        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->delete();
        return redirect()->route('user.inventory', ['id' => $inventoryItem->user_id])
            ->with('success', 'Item deleted successfully.');
    }

    //User Training Equipment
    public function showUserTrainingEquipment(Request $request)
    {
        $user = User::with('trainingEquipments.trainingEquipment')->findOrFail($request->input('id'));
        return view('users.user_training_equipment_index', compact('user'));
    }

    public function editUserTrainingEquipment(Request $request)
    {
        $allTrainingEquipments = TrainingEquipment::all();
        $userTrainingEquipment = UserTrainingEquipment::find($request->input('id'));
        $user = User::findOrFail($request->input('userId'));
        return view('users.user_training_equipment_form', compact('user', 'userTrainingEquipment', 'allTrainingEquipments'));
    }

    public function updateUserTrainingEquipment(Request $request)
    {
        $validated = $request->validate([
            'training_equipment_id' => 'required|exists:training_equipment,id',
            'stat_increase' => 'required|integer|min:1',
            'level' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($request->has('id')) {
            $userTrainingEquipment = UserTrainingEquipment::findOrFail($request->input('id'));
            $userTrainingEquipment->update($validated);
        } else {
            UserTrainingEquipment::create($validated);
        }
        return redirect()->route('user.training_equipment', ['id' => $validated['user_id']])
            ->with('success', 'Training equipment saved successfully.');
    }

    public function destroyUserTrainingEquipment($id)
    {
        $userTrainingEquipment = UserTrainingEquipment::findOrFail($id);
        $userTrainingEquipment->delete();
        return redirect()->route('user.training_equipment', ['id' => $userTrainingEquipment->user_id])
            ->with('success', 'Training equipment deleted successfully.');
    }
}
