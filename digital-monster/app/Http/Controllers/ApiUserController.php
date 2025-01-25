<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\UserDigitalMonster;
use App\Models\DigitalMonster;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Item;


class ApiUserController extends Controller
{
    public function getEggs()
    {
        $eggMonsters = DigitalMonster::where('stage', 'egg')->get();
        return response()->json([
            'status' => true,
            'message' => 'Eggs',
            'digitalMonsters' => $eggMonsters,
        ]);
    }

    public function getUserDigitalMonsters(Request $request)
    {
        $user = $request->user();
        $userDigitalMonsters = UserDigitalMonster::where('user_id', $user->id)->get();
        return response()->json([
            'status' => true,
            'userDigitalMonsters' => $userDigitalMonsters,
        ]);
    }

    public function switchUserDigitalMonster(Request $request)
    {
        $validated = $request->validate([
            'user_digital_monster_id' => 'required|exists:user_digital_monsters,id',
        ]);
        $user = $request->user();
        $userDigitalMonster = UserDigitalMonster::findOrFail($validated['user_digital_monster_id']);
        if ($userDigitalMonster->isMain) {
            return response()->json([
                'status' => false,
                'message' => 'This digital monster is already set as main.',
            ]);
        }
        UserDigitalMonster::where('user_id', $user->id)->update(['isMain' => false]);
        $userDigitalMonster->isMain = true;
        $userDigitalMonster->save();
        return response()->json([
            'status' => true,
            'message' => 'User digital monster switched to main successfully.',
            'userDigitalMonster' => $userDigitalMonster,
        ]);
    }

    public function saveUserDigitalMonster(Request $request)
    {
        $request->merge([
            'sleepStartedAt' => $request->input('sleepStartedAt') !== null 
                ? preg_replace('/\.\d+$/', '', $request->input('sleepStartedAt')) 
                : null,
        ]);        
        $validated = $request->validate([
            'id' => 'required|integer|exists:user_digital_monsters,id',
            'name' => 'sometimes|string|max:255',
            'level' => 'sometimes|integer|min:1',
            'exp' => 'sometimes|integer|min:0',
            'strength' => 'sometimes|integer|min:0',
            'agility' => 'sometimes|integer|min:0',
            'defense' => 'sometimes|integer|min:0',
            'mind' => 'sometimes|integer|min:0',
            'hunger' => 'sometimes|integer|min:0',
            'exercise' => 'sometimes|integer|min:0',
            'clean' => 'sometimes|integer|min:0',
            'energy' => 'sometimes|integer|min:0',
            'maxEnergy' => 'sometimes|integer|min:0',
            'wins' => 'sometimes|integer|min:0',
            'losses' => 'sometimes|integer|min:0',
            'trainings' => 'sometimes|integer|min:0',
            'maxTrainings' => 'sometimes|integer|min:0',
            'currentEvoPoints' => 'sometimes|integer|min:0',
            'sleepStartedAt' => 'sometimes|date_format:Y-m-d H:i:s|nullable',
        ]);

        $userDigitalMonster = UserDigitalMonster::find($validated['id']);

        if ($userDigitalMonster->user_id !== $request->user()->id) {
            $response = [
                'status' => false,
                'message' => 'Unauthorized action. This digital monster does not belong to you.',
            ];
            return response()->json($response, 403);
        }

        $userDigitalMonster->fill($validated);
        $userDigitalMonster->save();

        return response()->json([
            'status' => true,
            'message' => 'User Digital Monster saved successfully.',
        ]);
    }

}
