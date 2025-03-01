<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDigitalMonster;
use App\Models\DigitalMonster;
use App\Models\UserItem;
use App\Models\Item;
use App\Models\Equipment;
use App\Models\UserEquipment;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('profile.index', compact('users'));
    }

    public function profile()
    {
        $user = User::with(['digitalMonsters', 'userItems.item', 'userEquipment.equipment'])
            ->findOrFail(session('user_id'));
        return view('pages.profile', compact('user'));
    }

    //User Digital Monster
    public function editUserDigitalMonster()
    {
        $allDigitalMonsters = DigitalMonster::with('eggGroup')->get();
        if ($allDigitalMonsters->isEmpty()) {
            return redirect()->route('digital_monsters.index')->with('error', 'No digital monsters found.');
        }
        $userDigitalMonster = UserDigitalMonster::find(session('user_id'));
        $user = User::findOrFail(session('user_id'));
        return view('digital_monsters.user-form', compact('user', 'userDigitalMonster', 'allDigitalMonsters'));
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
            'user_id' => 'required|exists:users,id',
            'isMain' => 'required|integer'
        ]);

        if ($request->input('isMain') == 1) {
            UserDigitalMonster::where('user_id', $validated['user_id'])
                ->where('id', '!=', $request->input('id'))
                ->update(['isMain' => 0]);
        }
        if ($request->has('id')) {
            $userDigitalMonster = UserDigitalMonster::findOrFail($request->input('id'));
            $userDigitalMonster->update($validated);
        } else {
            $userDigitalMonster = UserDigitalMonster::create($validated);
        }
        return redirect()->route('user.profile', ['id' => $validated['user_id']])
            ->with('success', 'Digital Monster saved successfully.');
    }

    public function destroyUserDigitalMonster($id)
    {
        $userDigitalMonster = UserDigitalMonster::findOrFail($id);
        $userDigitalMonster->delete();
        return redirect()->route('user.profile', ['id' => $userDigitalMonster->user_id])
            ->with('success', 'Digital Monster deleted successfully.');
    }

    //Inventory
    public function editUserItem()
    {
        $allItems = Item::all();
        $userItem = UserItem::find(session('user_item_id'));
        return view('item.user_form', ['userItem' => $userItem, 'allItems' => $allItems]);
    }

    public function updateUserItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userItemData = $request->only(['item_id', 'quantity']);
        $item = Item::findOrFail($validated['item_id']);
        if (!in_array($item->type, ['Material', 'Consumable'])) {
            $validated = array_merge($validated, $request->validate([
                'equipped' => 'required|integer|in:0,1',
            ]));
        

            if ($validated['equipped'] == 1) {
                $userItems = UserItem::where('user_id', session('user_id'))
                    ->whereHas('item', function ($query) use ($item) {
                        $query->where('type', $item->type);
                    })
                    ->get();
                foreach ($userItems as $userItem) {
                    if ($userItem->equipped == 1) {
                        $userItem->update(['equipped' => 0]);
                    }
                }
            }
        } else {
            $userItemData['equipped'] = 0;
        }


        $user = User::findOrFail(session('user_id'));
        $userItemData['user_id'] = $user->id;



        if (session('user_item_id')) {
            $userItem = UserItem::findOrFail(session('user_item_id'));
            $userItem->update($userItemData);
            $message = 'User Item updated successfully.';
        } else {
            UserItem::create($userItemData);
            $message = 'User Item created successfully.';
        }

        return redirect()->route('user.profile')
            ->with('success', 'User Item saved successfully.');
    }

    public function destroyUserInventory($id)
    {
        $inventoryItem = Inventory::findOrFail($id);
        $inventoryItem->delete();
        return redirect()->route('user.profile', ['id' => $inventoryItem->user_id])
            ->with('success', 'Item deleted successfully.');
    }

    //User Training Equipment
    public function editUserEquipment()
    {
        $allEquipment = Equipment::all();
        $userEquipment = UserEquipment::find(session('user_equipment_id'));
        return view('equipment.user_form', ['userEquipment' => $userEquipment, 'allEquipment' => $allEquipment]);
    }

    public function updateUserEquipment(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'level' => 'required|integer|min:1',
        ]);

        $userEquipmentData = $request->only(['equipment_id', 'level']);
        $user = User::findOrFail(session('user_id'));
        $userEquipmentData['user_id'] = $user->id;

        if (session('user_equipment_id')) {
            $userEquipment = UserEquipment::findOrFail(session('user_equipment_id'));
            $userEquipment->update($userEquipmentData);
            $message = 'User Equipment updated successfully.';
        } else {
            UserEquipment::create($userEquipmentData);
            $message = 'User Equipment created successfully.';
        }

        return redirect()->route('user.profile')->with('success', $message);
    }

    public function destroyUserEquipment()
    {
        $userEquipment = UserEquipment::findOrFail(session('user_equipment_id'));
        $userEquipment->delete();
        return redirect()->route('user.profile')
            ->with('success', 'User Equipment deleted successfully.');
    }
}
