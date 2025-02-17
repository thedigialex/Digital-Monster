<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDigitalMonster;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userMonsters = UserDigitalMonster::with('digitalMonster')
            ->where('user_id', $user->id)
            ->whereHas('digitalMonster', function ($query) {
                $query->where('stage', '!=', 'Egg');
            })
            ->get();

        $totalMonsters = $userMonsters->count();

        return view('dashboard', compact('user', 'userMonsters', 'totalMonsters'));
    }
}
