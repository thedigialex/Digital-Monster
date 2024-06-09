<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DigitalMonster;
use App\Models\UserDigitalMonster;
use Illuminate\Http\Request;

class UserDigitalMonsterController extends Controller
{
    public function handleMonster(Request $request, $id, $monsterId = null)
    {
        $user = User::findOrFail($id);
        $monstersByEgg = DigitalMonster::all()->sortBy('egg_id')->sortBy('monster_id');
        $userDigitalMonster = $monsterId ? UserDigitalMonster::findOrFail($monsterId) : null;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'digital_monster_id' => 'required|exists:digital_monsters,id',
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'isMain' => 'required|boolean'
            ]);

            if ($userDigitalMonster) {
                $userDigitalMonster->update($validated);
                return redirect()->route('user.show', $id)->with('success', 'Digital Monster updated successfully.');
            } else {
                $user->userDigitalMonsters()->create($validated);
                return redirect()->route('user.show', $id)->with('success', 'Digital Monster created successfully.');
            }
        }

        return view('monsters.user_monster_edit', compact('user', 'monstersByEgg', 'userDigitalMonster'));
    }

    public function deleteMonster($id, $monsterId)
    {
        $userDigitalMonster = UserDigitalMonster::findOrFail($monsterId);
        $userDigitalMonster->delete();

        return redirect()->route('user.show', $id)->with('success', 'Digital Monster deleted successfully.');
    }
}
