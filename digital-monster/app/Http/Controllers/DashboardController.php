<?php

namespace App\Http\Controllers;

use App\Models\UserItem;
use App\Models\UserMonster;
use App\Models\UserEquipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userMonsters = UserMonster::with('monster')
            ->where('user_id', $user->id)
            ->whereHas('monster', function ($query) {
                $query->where('stage', '!=', 'Egg');
            })
            ->get();

        $userEquipment = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->whereHas('equipment', function ($query) {
                $query->whereNotIn('stat', ['Lighting', 'Cleaning']);
            })
            ->get();

        $userEquipmentLight = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->whereHas('equipment', function ($query) {
                $query->where('stat', 'Lighting');
            })
            ->first();

        $userItems = UserItem::with('item')
            ->where('user_id', $user->id)
            ->whereHas('item', function ($query) {
                $query->where('type', 'Consumable');
            })
            ->get();

        $totalMonsters = $userMonsters->count();

        return view('pages.dashboard', compact('user', 'userMonsters', 'totalMonsters', 'userEquipment', 'userEquipmentLight', 'userItems'));
    }

    public function useTraining(Request $request)
    {
        $user = Auth::user();
        $userMonster = UserMonster::where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $userEquipment = UserEquipment::with('equipment')
            ->where('id', $request->user_equipment_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || !$userEquipment) {
            return response()->json([
                'message' => 'Hmmm something is missing.',
            ], 404);
        }

        if ($userMonster->energy - 1 >= 0) {
            $userMonster->energy -= 1;
            $equipmentStat = $userEquipment->equipment->stat;
            $equipmentLevel = $userEquipment->level;
            $percentage = $request->percentage;

            switch ($equipmentStat) {
                case 'Strength':
                    $userMonster->strength += (5 * $equipmentLevel * $percentage) / 100;
                    break;
                case 'Agility':
                    $userMonster->agility += (5 * $equipmentLevel * $percentage) / 100;
                    break;
                case 'Defense':
                    $userMonster->defense += (5 * $equipmentLevel * $percentage) / 100;
                    break;
                case 'Mind':
                    $userMonster->mind += (5 * $equipmentLevel * $percentage) / 100;
                    break;
                case 'Cleaning':
                    $userMonster->cleaning += (5 * $equipmentLevel * $percentage) / 100;
                    break;
                case 'Lighting':
                    $userMonster->lighting += (5 * $equipmentLevel * $percentage) / 100;
                    break;
            }

            $userMonster->save();

            return response()->json([
                'message' => 'Training data updated successfully!',
                'userMonster' => $userMonster,
            ]);
        } else {
            return response()->json([
                'message' => 'Not enough energy',
            ]);
        }
    }

    public function useItem(Request $request)
    {
        $user = Auth::user();
        $userMonster = UserMonster::where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $userItem = UserItem::with('item')
            ->where('id', $request->user_item_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || !$userItem) {
            return response()->json([
                'message' => 'Hmmm something is missing.',
            ], 404);
        }

        if ($userItem->quantity > 0) {
            $effects = explode('-', $userItem->item->effect); 

            foreach ($effects as $effect) {
                list($type, $value) = explode(',', $effect);
                $value = (int) $value;

                switch ($type) {
                    case 'H':
                        $userMonster->hunger += $value;
                        break;
                    case 'e':
                        $userMonster->energy += $value;
                        break;
                }
            }

            $userMonster->hunger = min($userMonster->hunger, 4);
            $userMonster->energy = min($userMonster->energy, $userMonster->max_energy);

            $userMonster->save();

            $userItem->quantity -= 1;
            if ($userItem->quantity <= 0) {
                $userItem->delete();
            } else {
                $userItem->save();
            }

            return response()->json([
                'message' => 'Item used successfully!',
                'userMonster' => $userMonster,
                'userItemQuantity' => $userItem->quantity
            ], 200);
        }

        return response()->json([
            'message' => 'No items left to use.',
        ], 400);
    }
}
