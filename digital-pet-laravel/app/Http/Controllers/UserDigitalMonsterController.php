<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DigitalMonster;
use Illuminate\Http\Request;

class UserDigitalMonsterController extends Controller
{
    public function createMonster($id)
    {
        $user = User::findOrFail($id);
        $monstersByEgg = DigitalMonster::all()->sortBy('egg_id')->sortBy('monster_id');
        return view('monsters.user_monster_edit', compact('user', 'monstersByEgg'));
    }

    public function storeMonster(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'digital_monster_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
        ]);

        $user->userDigitalMonsters()->create($validatedData);
        return redirect()->route('user.show', $id)->with('success', 'Monster created successfully!');
    }
}
