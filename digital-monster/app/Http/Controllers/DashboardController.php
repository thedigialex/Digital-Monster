<?php

namespace App\Http\Controllers;

use App\Models\UserDigitalMonster;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userMonsters = UserDigitalMonster::with('digitalMonster')
            ->where('user_id', Auth::id())
            ->whereHas('digitalMonster', function ($query) {
                $query->where('stage', '!=', 'Egg');
            })
            ->get();

        return view('dashboard', compact('userMonsters'));
    }
}
