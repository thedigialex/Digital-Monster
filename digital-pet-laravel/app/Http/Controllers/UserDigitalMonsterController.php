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
        $monstersByEgg = DigitalMonster::all()->sortBy('eggId')->sortBy('monsterId');
        $userDigitalMonster = $monsterId ? UserDigitalMonster::findOrFail($monsterId) : null;

        $monsterOptions = $monstersByEgg->mapWithKeys(function($monster) {
            return [$monster->id => 'Egg: ' . $monster->eggGroup->name . ' | Monster Id: ' . $monster->monsterId];
        });

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

        return view('digitalMonsters.user-monster-edit', compact('user', 'monsterOptions', 'userDigitalMonster'));
    }

    public function deleteMonster($id, $monsterId)
    {
        $userDigitalMonster = UserDigitalMonster::findOrFail($monsterId);
        $userDigitalMonster->delete();

        return redirect()->route('user.show', $id)->with('success', 'Digital Monster deleted successfully.');
    }

    public function getUserDigitalMonsters(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $isMain = $request->query('isMain', null);
                $query = $user->userDigitalMonsters()->with('digitalMonster');
                if ($isMain !== null) {
                    $isMain = filter_var($isMain, FILTER_VALIDATE_BOOLEAN);
                    $query->where('isMain', $isMain);
                }

                $userDigitalMonsters = $query->get();
                return response()->json([
                    'status' => true,
                    'message' => 'User Digital Monsters Retrieved Successfully',
                    'userDigitalMonsters' => $userDigitalMonsters
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
