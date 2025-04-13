<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Event;
use App\Models\UserItem;
use App\Models\Monster;
use App\Models\UserMonster;
use App\Models\UserEquipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function garden()
    {
        $user = User::find(Auth::id());

        $userMonsters = UserMonster::with('monster')
            ->where('user_id', $user->id)
            ->get();

        $userEquipment = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->whereHas('equipment', function ($query) {
                $query->where('type', 'Stat');
            })
            ->get();

        $digiGarden = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->whereHas('equipment', function ($query) {
                $query->where('type', 'DigiGarden');
            })
            ->first();

        $allUserItems = UserItem::with('item')
            ->where('user_id', $user->id)
            ->get()
            ->groupBy(fn($userItem) => $userItem->item->type);

        $userItems = $allUserItems->get('Consumable', collect());
        $userAttacks = $allUserItems->get('Attack', collect());
        $userMaterials = $allUserItems->get('Material', collect());

        $background = $this->getUserBackgroundImage($user);
        $count = $userMonsters->count() . ' / ' . ($digiGarden->level * 5);

        return view('dashboard.garden', compact('user', 'userMonsters', 'count', 'userEquipment', 'userItems', 'userAttacks', 'userMaterials', 'background'));
    }

    public function gardenUser(Request $request)
    {
        $user = User::find(session('other_user_id'));
        $userMonsters = UserMonster::with('monster')
            ->where('user_id', $user->id)
            ->get();

        $digiGarden = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->whereHas('equipment', function ($query) {
                $query->where('type', 'DigiGarden');
            })
            ->first();

        $background = $this->getUserBackgroundImage($user);
        $count = $userMonsters->count() . ' / ' . ($digiGarden->level * 5);

        return view('dashboard.viewGarden', compact('user', 'userMonsters', 'count', 'background'));
    }

    public function colosseum()
    {
        $user = User::find(Auth::id());

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

        $background = $this->getUserBackgroundImage($user);

        return view('dashboard.colosseum', compact('userMonsters', 'background'));
    }

    public function generateBattle(Request $request)
    {
        $user = User::find(Auth::id());

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
        $level = rand(1, 5);

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

        $elementAdvantage = [
            'Fire' => ['Nature', 'Machine'],
            'Water' => ['Fire', 'Dark'],
            'Nature' => ['Water', 'Light'],
            'Machine' => ['Light', 'Nature'],
            'Light' => ['Dark', 'Water'],
            'Dark' => ['Machine', 'Fire']
        ];

        function getElementFromType($userMonster)
        {
            return match ($userMonster->type) {
                'Virus' => $userMonster->monster->element_2,
                'Vaccine' => $userMonster->monster->element_1,
                default => $userMonster->monster->element_0,
            };
        }

        $userElement = getElementFromType($userMonster);
        $enemyElement = getElementFromType($enemyUserMonster);

        $userHasTypeAdvantage = ($typeAdvantage[$userMonster->type]) == $enemyUserMonster->type;
        $enemyHasTypeAdvantage = ($typeAdvantage[$enemyUserMonster->type] ?? null) == $userMonster->type;

        $userHasElementAdvantage = $userElement && $enemyElement && in_array($enemyElement, $elementAdvantage[$userElement] ?? []);
        $enemyHasElementAdvantage = $userElement && $enemyElement && in_array($userElement, $elementAdvantage[$enemyElement] ?? []);

        function getMultiplier($hasTypeAdv, $hasElementAdv)
        {
            if ($hasTypeAdv && $hasElementAdv) return 1.6;
            if ($hasTypeAdv || $hasElementAdv) return 1.25;
            return 1;
        }

        $userMultiplier = getMultiplier($userHasTypeAdvantage, $userHasElementAdvantage);
        $enemyMultiplier = getMultiplier($enemyHasTypeAdvantage, $enemyHasElementAdvantage);

        $battleResult = [
            (($userMonster->strength * $userMultiplier) > ($enemyUserMonster->strength * $enemyMultiplier)) ? 1 : 0,
            (($userMonster->agility * $userMultiplier) > ($enemyUserMonster->agility * $enemyMultiplier)) ? 1 : 0,
            (($userMonster->defense * $userMultiplier) > ($enemyUserMonster->defense * $enemyMultiplier)) ? 1 : 0,
            (($userMonster->mind * $userMultiplier) > ($enemyUserMonster->mind * $enemyMultiplier)) ? 1 : 0
        ];

        $indexes = array_rand($battleResult, 3);
        $animationFrame = array_intersect_key($battleResult, array_flip($indexes));
        $sum = array_sum($animationFrame);

        if ($sum >= 2) {
            $userMonster->exp += (5 * $level);
            $expRequired = ($userMonster->level * 50) + (($userMonster->level * 50) / 2);
            if ($userMonster->exp >= $expRequired &&  $userMonster->level < 10) {
                $userMonster->level += 1;
                $userMonster->exp -= $expRequired;
            }
            $user->bits += (5 * $userMonster->level);
            $user->save();
            $userMonster->wins++;
            $userMonster->evo_points = min($userMonster->evo_points + 10, $userMonster->monster->evo_requirement);
        } else {
            $userMonster->losses++;
        }

        $userMonster->energy--;

        $userMonster->save();

        $removeUserMonster = ($userMonster->energy == 0);

        return response()->json([
            'successful' => true,
            'enemyUserMonster' => $enemyUserMonster,
            'animationFrame' => $animationFrame,
            'removeUserMonster' => $removeUserMonster
        ]);
    }

    public function adventure()
    {
        $user = User::find(Auth::id());

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

        $background = $this->getUserBackgroundImage($user);

        return view('dashboard.adventure', compact('userMonsters', 'background'));
    }

    public function generateStep(Request $request)
    {
        $user = User::find(Auth::id());

        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();


        if (!$userMonster || $userMonster->sleep_at || $userMonster->energy <= 0) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ]);
        }

        $userMonster->steps += 1;
        $userMonster->save();

        $event = Event::inRandomOrder()->first();

        if ($event->item_id) {
            $item = Item::find($event->item_id);
            $userItem = UserItem::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->first();

            if ($userItem) {
                if ($userItem->quantity < $item->max_quantity) {
                    $userItem->quantity += 1;
                    $userItem->save();
                }
            } else {
                UserItem::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'quantity' => 1,
                ]);
            }
        }

        return response()->json([
            'successful' => true,
            'message' => "{$userMonster->name} " . $event->message,
            'duration' => rand(3, 5) * 1000,
        ]);
    }

    public function shop()
    {
        $user = User::find(Auth::id());

        $background = $this->getUserBackgroundImage($user);

        $userItems = UserItem::where('user_id', $user->id)->get()->keyBy('item_id');

        $items = Item::where('type', '!=', 'Material')
            ->get()
            ->filter(function ($item) use ($userItems) {
                if (isset($userItems[$item->id])) {
                    return $userItems[$item->id]->quantity < $item->max_quantity;
                }
                return true;
            })
            ->filter(function ($item) {
                return $item->available == 1;
            })
            ->groupBy('type');

        return view('dashboard.shop', compact('user', 'background', 'items'));
    }

    public function upgradeStation()
    {
        $user = User::find(Auth::id());

        $background = $this->getUserBackgroundImage($user);

        $allUserItems = UserItem::with('item')
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('item_id');

        $userEquipments = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->get()
            ->filter(function ($userEquipment) use ($allUserItems) {
                $equipment = $userEquipment->equipment;
                if (!$equipment || !$equipment->upgrade_item_id) {
                    return false;
                }
                if ($userEquipment->level >= $equipment->max_level) {
                    return false;
                }
                $requiredQty = $userEquipment->level * 10;
                $userItem = $allUserItems->get($equipment->upgrade_item_id);
                return $userItem && $userItem->quantity >= $requiredQty;
            });
        return view('dashboard.upgrade', compact('user', 'userEquipments', 'background'));
    }

    public function upgradeEquipment(Request $request)
    {
        $user = User::find(Auth::id());
        $userEquipment = UserEquipment::with('equipment')->find($request->equipment_id);
        $equipment = $userEquipment->equipment;
        $requiredQty = $userEquipment->level * 10;

        $userItem = UserItem::where('user_id', $user->id)
            ->where('item_id', $equipment->upgrade_item_id)
            ->first();

        if (!$userEquipment || $userEquipment->user_id !== $user->id || $userEquipment->level >= $equipment->max_level || !$userItem || $userItem->quantity < $requiredQty) {
            return response()->json(['message' => 'Hmmm something is off.', 'successful' => false]);
        }

        $userEquipment->level += 1;
        $userEquipment->save();

        $userItem->quantity -= $requiredQty;
        $userItem->quantity <= 0 ? $userItem->delete() : $userItem->save();

        return response()->json([
            'message' => 'Equipment upgraded successfully!',
            'successful' => true,
            'new_level' => $userEquipment->level,
        ]);
    }

    public function buyItem(Request $request)
    {
        $user = User::find(Auth::id());
        $item = Item::find($request->item_id);

        if (!$item || $user->bits < $item->price) {
            return response()->json(['message' => 'Hmmm something is off.', 'successful' => false]);
        }

        $userItem = UserItem::firstOrNew(['user_id' => $user->id, 'item_id' => $item->id]);

        if ($userItem->exists && $userItem->quantity >= $item->max_quantity) {
            return response()->json(['message' => 'Hmmm something is off.', 'successful' => false]);
        }

        $userItem->quantity = ($userItem->exists ? $userItem->quantity + 1 : 1);
        $userItem->save();

        $user->bits -= $item->price;

        $user->save();

        $removeItem = $userItem->quantity >= $item->max_quantity;

        return response()->json([
            'message' => 'Item purchased successfully!',
            'successful' => true,
            'removeItem' => $removeItem,
            'newBalance' => $user->bits
        ]);
    }

    public function converge()
    {
        $user = User::find(Auth::id());
        $background = $this->getUserBackgroundImage($user);

        $count = $user->userItems()
            ->whereHas('item', function ($query) {
                $query->where('type', 'Material')
                    ->where('name', 'DataCrystal');
            })
            ->sum('quantity');

        $userMonsters = UserMonster::with('monster')->where('user_id', $user->id)->get();
        $totalMonsters = $userMonsters->count();

        $digiGarden = UserEquipment::with('equipment')
            ->where('user_id', $user->id)
            ->whereHas('equipment', function ($query) {
                $query->where('type', 'DigiGarden');
            })
            ->first();

        $message = $count >= 10
            ? ($totalMonsters >= $digiGarden->level * 5
                ? "Not enough room"
                : "Select an Egg")
            : "Not enough DataCrystals";

        $eggs = ($count >= 10 && $totalMonsters < $digiGarden->level * 5)
            ? Monster::where('stage', 'Egg')->get()
            : [];

        return view('dashboard.converge', compact('count', 'background', 'eggs', 'message'));
    }

    public function convergeCreate(Request $request)
    {
        $user = User::find(Auth::id());
        $monster = Monster::find($request->monster_id);

        $count = $user->userItems()
            ->whereHas('item', function ($query) {
                $query->where('type', 'Material')
                    ->where('name', 'DataCrystal');
            })
            ->sum('quantity');

        if ($count < 10 || !$monster) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false,
            ]);
        }

        $userAttacks = UserItem::with('item')
            ->where('user_id', $user->id)
            ->whereHas('item', function ($query) {
                $query->where('type', 'Attack');
            })
            ->get();

        $types = ['Data', 'Virus', 'Vaccine'];
        $userMonster = new UserMonster();
        $userMonster->user_id = $user->id;
        $userMonster->attack = $userAttacks->first()->id;
        $userMonster->monster_id = $monster->id;
        $userMonster->name = 'New Egg';
        $userMonster->type = $types[array_rand($types)];
        $userMonster->save();

        $user->userItems()
            ->whereHas('item', function ($query) {
                $query->where('type', 'Material')
                    ->where('name', 'DataCrystal');
            })
            ->delete();

        return response()->json([
            'message' => 'Egg created successfully!',
            'successful' => true,
        ]);
    }

    public function useTraining(Request $request)
    {
        $user = User::find(Auth::id());
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $userEquipment = UserEquipment::with('equipment')
            ->where('id', $request->user_equipment_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || !$userEquipment || $userMonster->sleep_at != null || $userMonster->monster->stage == "Egg") {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ], 404);
        }

        if ($userMonster->energy - 1 >= 0) {
            $userMonster->energy -= 1;

            if ($userMonster->max_trainings > $userMonster->trainings) {
                $userMonster->trainings += 1;
                $equipmentStat = $userEquipment->equipment->stat;
                $equipmentLevel = $userEquipment->level;
                $percentage = round($request->percentage);

                $statIncrease = round((5 * $equipmentLevel * $percentage) / 100);

                switch ($equipmentStat) {
                    case 'Strength':
                        $userMonster->strength += $statIncrease;
                        if ($userMonster->monster->type == 'Virus') {
                            $userMonster->strength += round($statIncrease / 5);
                        }
                        break;
                    case 'Agility':
                        $userMonster->agility += $statIncrease;
                        if ($userMonster->monster->type == 'Data') {
                            $userMonster->agility += round($statIncrease / 5);
                        }
                        break;
                    case 'Defense':
                        $userMonster->defense += $statIncrease;
                        if ($userMonster->monster->type == 'Vaccine') {
                            $userMonster->defense += round($statIncrease / 5);
                        }
                        break;
                    case 'Mind':
                        $userMonster->mind += $statIncrease;
                        break;
                }
            }

            if (rand(1, 100) <= 30) {
                $userMonster->hunger = max(0, $userMonster->hunger - 1);
            }

            $userMonster->save();

            return response()->json([
                'message' => 'Training data updated successfully!',
                'userMonster' => $userMonster,
                'successful' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Not enough energy',
                'successful' => false
            ]);
        }
    }

    public function useItem(Request $request)
    {
        $user = User::find(Auth::id());
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $userItem = UserItem::with('item')
            ->where('id', $request->user_item_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || !$userItem || $userMonster->hunger == 4 || $userMonster->sleep_at != null || $userMonster->monster->stage == "Egg") {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
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
            'userItemQuantity' => $userItem->quantity,
            'successful' => true
        ], 200);
    }

    public function changeAttack(Request $request)
    {
        $user = User::find(Auth::id());
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        $userItem = UserItem::with('item')
            ->where('id', $request->user_attack_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || !$userItem) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ], 404);
        }

        $userMonster->attack = $request->user_attack_id;

        $userMonster->save();

        return response()->json([
            'message' => 'Attack Equipped successfully!',
            'successful' => true
        ], 200);
    }

    public function sleepToggle(Request $request)
    {
        $user = User::find(Auth::id());
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || $userMonster->monster->stage == "Egg") {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ], 404);
        }
        if ($userMonster->sleep_time == null) {
            $userMonster->sleep_time = now();
        } else {
            $minutesSinceSleep = Carbon::parse($userMonster->sleep_time)->diffInMinutes(now());
            $userMonster->energy = min(
                $userMonster->energy + floor((($minutesSinceSleep / 10) * 5) / 100 * $userMonster->max_energy),
                $userMonster->max_energy
            );
            $userMonster->sleep_time = null;
        }

        $userMonster->save();

        return response()->json([
            'message' => 'Sleep toggled successfully!',
            'userMonster' => $userMonster,
            'successful' => true
        ]);
    }

    public function evolve(Request $request)
    {
        $user = User::find(Auth::id());
        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || ($userMonster->evo_points < $userMonster->monster->evo_requirement)) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ], 404);
        }

        $user->exp += (5 * $userMonster->level);
        $expRequired = ($user->level * 150) + (($user->level * 150) / 2);
        if ($user->exp >= $expRequired &&  $user->level < 10) {
            $user->level += 1;
            $user->exp -= $expRequired;
        }
        $user->save();

        $userMonster->evolve();
        $userMonster->refresh();

        return response()->json([
            'message' => 'Evolved successfully!',
            'userMonster' => $userMonster,
            'successful' => true
        ]);
    }

    public function getUserBackgroundImage($user)
    {
        $userBackground = UserItem::with('item')
            ->where('id', $user->background_id)
            ->first();

        $hour = now()->hour;
        if ($hour >= 12 && $hour < 18) {
            $background = "/storage/" . $userBackground->item->image;
        } elseif ($hour >= 0 && $hour < 6) {
            $background = "/storage/" . $userBackground->item->image_2;
        } else {
            $background = "/storage/" . $userBackground->item->image_1;
        }
        return $background;
    }

    public function changeName(Request $request)
    {
        $userMonster = UserMonster::where('id', $request->user_monster_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$userMonster) {
            return response()->json(['message' => 'Hmmm something is off.', 'successful' => false]);
        }

        $name = strip_tags($request->name);
        $name = preg_replace('/[^a-zA-Z0-9\s\-\_]/', '', $name);

        $badWords = config('bannedWords.list');

        foreach ($badWords as $badWord) {
            if (stripos($name, $badWord) !== false) {
                $name = 'monster';
            }
        }

        $userMonster->name = substr($name, 0, 12);
        $userMonster->save();

        return response()->json([
            'message' => 'Name changed successfully!',
            'newName' => $userMonster->name,
            'successful' => true,
        ]);
    }
}
