<?php

namespace App\Http\Controllers;

use App\Models\UserMonster;
use Illuminate\Support\Facades\Auth;

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

        $totalMonsters = $userMonsters->count();

        return view('pages.dashboard', compact('user', 'userMonsters', 'totalMonsters'));
    }
}
