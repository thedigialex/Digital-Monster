<?php

namespace App\Http\Controllers;

use App\Models\Item;
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

        $userAttacks = UserItem::with('item')
            ->where('user_id', $user->id)
            ->whereHas('item', function ($query) {
                $query->where('type', 'Attack');
            })
            ->get();

        $totalMonsters = $userMonsters->count();

        return view('pages.dashboard', compact('user', 'userMonsters', 'totalMonsters', 'userEquipment', 'userEquipmentLight', 'userItems', 'userAttacks'));
    }

    public function colosseum()
    {
        $user = Auth::user();

        $userMonsters = UserMonster::with('monster')
            ->where('user_id', $user->id)
            ->whereHas('monster', function ($query) {
                $query->whereNotIn('stage', ['Egg', 'Fresh', 'Child']);
            })
            ->where('energy', '>', 0)
            ->whereNull('sleep_time')
            ->get();

        foreach ($userMonsters as $userMonster) {
            $userMonster->attack = UserItem::with('item')->where('id', $userMonster->attack)->first();
        }

        return view('pages.colosseum', compact('user', 'userMonsters',));
    }

    public function generateBattle(Request $request)
    {
        $user = Auth::user();

        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $stages = ['Rookie', 'Champion', 'Ultimate', 'Mega'];

        $stageIndex = array_search($userMonster->monster->stage, $stages);
        $validStages = [$userMonster->monster->stage];

        if ($stageIndex > 0) {
            $validStages[] = $stages[$stageIndex - 1];
        }

        $enemyStage = $validStages[array_rand($validStages)];
        $enemyMonster = Monster::where('stage', $enemyStage)->inRandomOrder()->first();

        if (!$userMonster || !$enemyMonster || $userMonster->sleep_at || $userMonster->energy <= 0) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ]);
        }

        $types = ['Data', 'Virus', 'Vaccine'];
        $level = rand(1, 10);

        $randomAttackItem = Item::where('type', 'Attack')
            ->inRandomOrder()
            ->first();

        $enemyUserMonster = new UserMonster([
            'monster_id' => $enemyMonster->id,
            'name' => 'Wild Monster',
            'type' => $types[array_rand($types)],
            'level' => $level,
            'attack' => $randomAttackItem,
            'strength' => rand(25, 50 * $level) * (array_search($enemyStage, $stages) + 1),
            'agility' => rand(25, 50 * $level) * (array_search($enemyStage, $stages) + 1),
            'defense' => rand(25, 50 * $level) * (array_search($enemyStage, $stages) + 1),
            'mind' => rand(25, 50 * $level) * (array_search($enemyStage, $stages) + 1),
        ]);

        $enemyUserMonster->setRelation('monster', $enemyMonster);

        $typeAdvantage = [
            'Data' => 'Virus',
            'Virus' => 'Vaccine',
            'Vaccine' => 'Data'
        ];

        $userHasAdvantage = ($typeAdvantage[$userMonster->type] ?? null) === $enemyUserMonster->type;
        $enemyHasAdvantage = ($typeAdvantage[$enemyUserMonster->type] ?? null) === $userMonster->type;

        $battleResult = [
            (($userMonster->strength * ($userHasAdvantage ? 1.3 : ($enemyHasAdvantage ? 0.7 : 1))) >
                ($enemyUserMonster->strength * ($enemyHasAdvantage ? 1.3 : ($userHasAdvantage ? 0.7 : 1)))) ? 1 : 0,

            (($userMonster->agility * ($userHasAdvantage ? 1.3 : ($enemyHasAdvantage ? 0.7 : 1))) >
                ($enemyUserMonster->agility * ($enemyHasAdvantage ? 1.3 : ($userHasAdvantage ? 0.7 : 1)))) ? 1 : 0,

            (($userMonster->defense * ($userHasAdvantage ? 1.3 : ($enemyHasAdvantage ? 0.7 : 1))) >
                ($enemyUserMonster->defense * ($enemyHasAdvantage ? 1.3 : ($userHasAdvantage ? 0.7 : 1)))) ? 1 : 0,

            (($userMonster->mind * ($userHasAdvantage ? 1.3 : ($enemyHasAdvantage ? 0.7 : 1))) >
                ($enemyUserMonster->mind * ($enemyHasAdvantage ? 1.3 : ($userHasAdvantage ? 0.7 : 1)))) ? 1 : 0
        ];

        $indexes = array_rand($battleResult, 3);
        $animationFrame = array_intersect_key($battleResult, array_flip($indexes));
        $sum = array_sum($animationFrame);

        $sum >= 2 ? $userMonster->wins++ : $userMonster->losses++;
        $userMonster->energy--;

        $userMonster->save();

        $removeUserMonster = ($userMonster->energy == 0) ? true : false;

        return response()->json([
            'successful' => true,
            'enemyUserMonster' => $enemyUserMonster,
            'animationFrame' => $animationFrame,
            'removeUserMonster' => $removeUserMonster
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
