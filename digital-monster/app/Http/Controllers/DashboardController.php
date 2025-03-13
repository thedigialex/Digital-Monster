<?php

namespace App\Http\Controllers;

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
            ->get();
        $totalMonsters = $userMonsters->count();

        return view('pages.dashboard', compact('user', 'userMonsters', 'totalMonsters', 'userEquipment'));
    }


    public function updateTraining(Request $request)
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

        if ($userMonster->energy - 1 > 0) {
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
            ]);
        } else {
            return response()->json([
                'message' => 'Not enough energy',
            ]);
        }
    }
}
