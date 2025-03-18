<?php

namespace App\Http\Controllers;

use App\Models\UserItem;
use App\Models\UserMonster;
use App\Models\UserEquipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
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
            $percentage = round($request->percentage);
            $statIncrease = round((5 * $equipmentLevel * $percentage) / 100);

            switch ($equipmentStat) {
                case 'Strength':
                    $userMonster->strength += $statIncrease;
                    break;
                case 'Agility':
                    $userMonster->agility += $statIncrease;
                    break;
                case 'Defense':
                    $userMonster->defense += $statIncrease;
                    break;
                case 'Mind':
                    $userMonster->mind += $statIncrease;
                    break;
                case 'Cleaning':
                    $userMonster->cleaning += $statIncrease;
                    break;
                case 'Lighting':
                    $userMonster->lighting += $statIncrease;
                    break;
            }

            if (rand(1, 100) <= 30) {
                $userMonster->hunger = max(0, $userMonster->hunger - 1);
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
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $userItem = UserItem::with('item')
            ->where('id', $request->user_item_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || !$userItem || $userMonster->hunger == 4) {
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
                    case 'EVO':
                        $userMonster->evo_points += $value;
                        $userMonster->evo_points = min($userMonster->evo_points, $userMonster->monster->evo_requirement);                        
                        break;
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

    public function sleepToggle(Request $request)    {
        $user = Auth::user();
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
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
        if ($userMonster->sleep_time == null) {
            $userMonster->sleep_time = now();
        }
        else{
            $userMonster->sleep_time = null;
            $minutesSinceSleep = Carbon::parse($userMonster->sleep_time)->diffInMinutes(now());
            $userMonster->energy = min(
                $userMonster->energy + floor((($minutesSinceSleep / 10) * 4 + $userEquipment->level) / 100 * $userMonster->max_energy),
                $userMonster->max_energy
            );
        }

        $userMonster->save();
        
        return response()->json([
            'message' => 'Sleep toggled successfully!',
            'userMonster' => $userMonster,
        ]);
    }
}
