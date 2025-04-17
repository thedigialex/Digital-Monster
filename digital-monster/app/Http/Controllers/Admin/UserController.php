<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Item;
use App\Models\Monster;
use App\Models\UserItem;
use App\Models\Equipment;
use App\Models\UserMonster;
use Illuminate\Http\Request;
use App\Models\UserEquipment;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function profile()
    {
        $user = User::with(['userMonsters.monster', 'userItems.item', 'userEquipment.equipment', 'userLocations.location'])
            ->findOrFail(session('user_edit_id'));
        return view('pages.user', ['user' => $user]);
    }

    //User Monster
    public function editUserMonster()
    {
        $allMonsters = Monster::with('eggGroup')->get();
        $userMonster = UserMonster::find(session('user_monster_id'));
        return view('monsters.user_form', compact('userMonster', 'allMonsters'));
    }

    public function updateUserMonster(Request $request)
    {
        $userMonster = UserMonster::find(session('user_monster_id'));

        $rules = [
            'name' => 'required|string|max:255',
            'monster_id' => 'required|exists:monsters,id',
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
            'max_energy' => 'required|integer|min:0',
            'wins' => 'required|integer|min:0',
            'losses' => 'required|integer|min:0',
            'trainings' => 'required|integer|min:0',
            'max_trainings' => 'required|integer|min:0',
            'evo_points' => 'required|integer|min:0',
            'main' => 'required|integer'
        ];

        if (!$userMonster) {
            $userMonster = new UserMonster();
            $userMonster->user_id =  session('user_edit_id');
        }

        $validationData = $request->validate($rules);

        $userMonster->fill($validationData);
        $userMonster->save();

        $message = session('user_monster_id') ? 'User Monster updated successfully.' : 'User Monster created successfully.';

        return redirect()->route('user.profile')->with('success', $message);
    }

    public function destroyUserMonster()
    {
        $userMonster = UserMonster::findOrFail(session('user_monster_id'));
        $userMonster->delete();
        return redirect()->route('user.profile')->with('success', 'Monster deleted successfully.');
    }

    //User Item
    public function editUserItem()
    {
        $allItems = Item::all();
        $userItem = UserItem::find(session('user_item_id'));
        return view('item.user_form', compact('userItem', 'allItems'));
    }

    public function updateUserItem(Request $request)
    {
        $userItem = UserItem::find(session('user_item_id'));
        $rules = [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ];

        if (!$userItem) {
            $userItem = new UserItem();
            $userItem->user_id =  session('user_edit_id');
        }

        $validationData = $request->validate($rules);

        $userItem->fill($validationData);
        $userItem->save();

        $message = session('user_item_id') ? 'User Item updated successfully.' : 'User Item created successfully.';

        return redirect()->route('user.profile')->with('success', $message);
    }

    public function destroyUserItem()
    {
        $userItem = UserItem::find(session('user_item_id'));
        $userItem->delete();
        return redirect()->route('user.profile')
            ->with('success', 'User Item deleted successfully.');
    }

    //User Equipment
    public function editUserEquipment()
    {
        $allEquipment = Equipment::all();
        $userEquipment = UserEquipment::find(session('user_equipment_id'));
        return view('equipment.user_form', compact('userEquipment', 'allEquipment'));
    }

    public function updateUserEquipment(Request $request)
    {
        $userEquipment = UserEquipment::find(session('user_equipment_id'));

        $rules = [
            'equipment_id' => 'required|exists:equipment,id',
            'level' => 'required|integer|min:1',
        ];

        if (!$userEquipment) {
            $userEquipment = new UserEquipment();
            $userEquipment->user_id = session('user_edit_id');
        }

        $validationData = $request->validate($rules);

        $userEquipment->fill($validationData);
        $userEquipment->save();

        $message = session('user_item_id') ? 'User Equipment updated successfully.' : 'User Equipment created successfully.';

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
