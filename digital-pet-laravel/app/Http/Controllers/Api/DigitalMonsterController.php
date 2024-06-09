<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DigitalMonster;

class DigitalMonsterController extends Controller
{
    public function getUserDigitalMonsters(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $isMain = $request->query('isMain', null);
                if ($isMain !== null) {
                    $isMain = filter_var($isMain, FILTER_VALIDATE_BOOLEAN);
                }
                $query = $user->userDigitalMonsters()->with('digitalMonster');
                if ($isMain !== null) {
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

    public function getDigitalMonsterByEggAndMonsterId(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $eggId = $request->query('egg_id');
                $monsterId = $request->query('monster_id');
                $digitalMonster = DigitalMonster::where('egg_id', $eggId)
                    ->where('monster_id', $monsterId)
                    ->first();
                return response()->json([
                    'status' => true,
                    'message' => 'Digital Monster Retrieved Successfully',
                    'digitalMonster' => $digitalMonster
                ], 200);
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
