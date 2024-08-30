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

        $monsterOptions = $monstersByEgg->mapWithKeys(function ($monster) {
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



    public function getUserDigitalMonster(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $userDigitalMonster = $user->userDigitalMonsters()
                    ->with('digitalMonster')
                    ->where('isMain', 1)
                    ->first();

                if ($userDigitalMonster) {
                    return response()->json([
                        'status' => true,
                        'message' => 'User Digital Monster Retrieved Successfully',
                        'userDigitalMonster' => $userDigitalMonster
                    ], 200);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'No main User Digital Monster found'
                ], 404);
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

    public function getDigitalMonsters(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                if ($request->query('eggReturn') != null) {
                    $query = DigitalMonster::where('monsterId', 0);
                    $digitalMonsters = $query->get();
                    return response()->json([
                        'status' => true,
                        'message' => 'Digital Monsters Retrieved Successfully',
                        'digitalMonsters' => $digitalMonsters
                    ], 200);
                } elseif ($request->query('battleStage') != null) {
                    //return random digitalmonsters based on stage 
                    //will add later ignoring for now
                } else {
                    $eggId = $request->query('eggId');
                    $monsterId = $request->query('monsterId');
                    if ($eggId !== null && $monsterId !== null) {
                        $digitalMonsters = DigitalMonster::where('eggId', $eggId)
                            ->where('monsterId', $monsterId)
                            ->get();

                        if ($digitalMonsters->isNotEmpty()) {
                            return response()->json([
                                'status' => true,
                                'message' => 'Digital Monsters Retrieved Successfully',
                                'digitalMonsters' => $digitalMonsters
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'No Digital Monsters Found'
                            ], 404);
                        }
                    }
                }
            }
            return response()->json([
                'status' => false,
                'message' => 'Invalid parameters'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
