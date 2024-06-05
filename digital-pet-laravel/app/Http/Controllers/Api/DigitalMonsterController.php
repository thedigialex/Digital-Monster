<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DigitalMonsterController extends Controller
{

    public function getUserDigitalMonsters(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $userDigitalMonsters = $user->userDigitalMonsters()->with('digitalMonster')->get();
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
