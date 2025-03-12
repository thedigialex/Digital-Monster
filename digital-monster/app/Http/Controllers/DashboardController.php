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

        return view('pages.dashboard', compact('user', 'userMonsters', 'totalMonsters','userEquipment'));
    }


    public function trainMonster()
    {
        $user = Auth::user();
        $userMonster = UserMonster::find(session('user_monster_id'));
        $userEquipment = UserMonster::find(session('user_equipment_id'));


        return view('user.training', compact('user', 'userMonster', 'userEquipment'));
    }

    public function updateTraining(Request $request)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
            'equipment_id' => 'required|exists:user_equipment,id',
        ]);

        $userEquipment = UserEquipment::findOrFail($request->equipment_id);
        $trainingGain = round($request->percentage / 10); // Example: Scale training gain from 0-10
        $userEquipment->level += $trainingGain;
        $userEquipment->save();

        return redirect()->route('trainMonster')->with('success', 'Training successful! Equipment leveled up.');
    }
}
