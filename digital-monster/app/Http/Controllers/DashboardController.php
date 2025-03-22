<?php

namespace App\Http\Controllers;

use App\Models\UserItem;
use App\Models\Monster;
use App\Models\UserMonster;
use App\Models\UserEquipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
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

    public function colosseum()
    {
        $user = Auth::user();

        $userMonsters = UserMonster::with('monster')
            ->where('user_id', $user->id)
            ->whereHas('monster', function ($query) {
                $query->whereNotIn('stage', ['Egg', 'Fresh', 'Child']);
            })
            ->get();
        //while ($userMonsters->count() < 20) {
        //    $userMonsters = $userMonsters->concat($userMonsters);
        //}

        //// Limit to exactly 20 monsters
        //$userMonsters = $userMonsters->take(17);
        //only monster with energy > 0 and not asleep
        return view('pages.colosseum', compact('user', 'userMonsters',));
    }

    public function generateBattle(Request $request)
    {
        //return [0,0,0,0] which is the animation frames
        //return enemy monsters to show along with a random type b/c type is tied to userMonster
        //compre stats into a 4 array but ranomize the elments and use the first three to check if a win or lose
        //update usermonster 
        //remove 1 energy. Check if userMonster has >0 energy
        //return a true or false to remove the activeuser monster
        $user = Auth::user();

        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $stages = ['Rookie', 'Champion', 'Ultimate', 'Mega'];
        $currentStage = $userMonster->monster->stage;
        $validStages = [$currentStage];

        if ($currentStage != 'Rookie') {
            $stageIndex = array_search($currentStage, $stages);
            $validStages[] = $stages[$stageIndex - 1] ?? 'Rookie';
        }

        $enemyStage = $validStages[array_rand($validStages)];
        $enemyMonster = Monster::where('stage', $enemyStage)->inRandomOrder()->first();

        if (!$userMonster || !$enemyMonster || $userMonster->sleep_at != null || $userMonster->energy-1 < 0) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ]);
        }

        $types = ['Data', 'Virus', 'Vaccine'];
        $randomType = $types[array_rand($types)];

        $enemyUserMonster = new UserMonster([
            'user_id' => null,
            'monster_id' => $enemyMonster->id,
            'name' => 'Wild Monster',
            'type' => $randomType,
            'attack' => rand(10, 50) * (array_search($enemyStage, $stages) + 1),
            'level' => rand(1, 20),
            'exp' => rand(0, 1000),
            'strength' => rand(10, 50) * (array_search($enemyStage, $stages) + 1),
            'agility' => rand(10, 50) * (array_search($enemyStage, $stages) + 1),
            'defense' => rand(10, 50) * (array_search($enemyStage, $stages) + 1),
            'mind' => rand(10, 50) * (array_search($enemyStage, $stages) + 1),
            'hunger' => rand(0, 100),
            'exercise' => rand(0, 100),
            'clean' => rand(0, 100),
            'energy' => rand(50, 100),
            'max_energy' => 100,
            'wins' => 0,
            'losses' => 0,
            'trainings' => 0,
            'max_trainings' => 10,
            'evo_points' => 0,
            'sleep_time' => null,
        ]);
        $enemyUserMonster->setRelation('monster', $enemyMonster);

        return response()->json([
            'message' => 'Enemy Monster Generated',
            'successful' => true,
            'enemyUserMonster' => $enemyUserMonster,
        ]);
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

        if (!$userMonster || !$userEquipment || $userMonster->sleep_at != null) {
            return response()->json([
                'message' => 'Hmmm something is off.',
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

        if (!$userMonster || !$userItem || $userMonster->hunger == 4 || $userMonster->sleep_at != null) {
            return response()->json([
                'message' => 'Hmmm something is off.',
            ], 404);
        }

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

    public function sleepToggle(Request $request)
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
        if ($userMonster->sleep_time == null) {
            $userMonster->sleep_time = now();
        } else {
            $minutesSinceSleep = Carbon::parse($userMonster->sleep_time)->diffInMinutes(now());
            $userMonster->energy = min(
                $userMonster->energy + floor((($minutesSinceSleep / 10) * 4 + $userEquipment->level) / 100 * $userMonster->max_energy),
                $userMonster->max_energy
            );
            $userMonster->sleep_time = null;
        }

        $userMonster->save();

        return response()->json([
            'message' => 'Sleep toggled successfully!',
            'userMonster' => $userMonster,
        ]);
    }

    public function evolve(Request $request)
    {
        $user = Auth::user();
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || ($userMonster->evo_points < $userMonster->monster->evo_requirement)) {
            return response()->json([
                'message' => 'Hmmm something is missing.',
            ], 404);
        }

        $userMonster->evolve();
        $userMonster->refresh();

        return response()->json([
            'message' => 'Evolved successfully!',
            'userMonster' => $userMonster,
        ]);
    }
}
