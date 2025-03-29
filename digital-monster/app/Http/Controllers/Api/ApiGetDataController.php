<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\DigitalMonster;
use App\Models\UserDigitalMonster;
use App\Http\Controllers\Controller;

class ApiGetDataController extends Controller
{
    public function getEggs(Request $request) {
        $user = $request->user();
        if ($user) {
            $eggs = DigitalMonster::where('stage', 'egg')->get();
            return response()->json([
                'status' => true,
                'message' => 'Digital Monster Eggs',
                'digitalMonsters' => $eggs,
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Token is invalid']);
    }

    public function getUserDigitalMonsters(Request $request) {
        $user = $request->user();
        if ($user) {
            $userDigitalMonsters = $user->digitalMonsters()->with('digitalMonster')->get();
            return response()->json([
                'status' => true,
                'message' => 'User Digital Monsters',
                'userDigitalMonsters' => $userDigitalMonsters,
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Token is invalid']);
    }

    public function getUserTrainingEquipment(Request $request) {
        $user = $request->user();
        if ($user) {
            $trainingEquipment = $user->trainingEquipments()->with('trainingEquipment')->get();
            return response()->json([
                'status' => true,
                'message' => 'User Training Equipment',
                'userTrainingEquipment' => $trainingEquipment,
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Token is invalid']);
    }

    public function getUserInventories(Request $request) {
        $user = $request->user();
        if ($user) {
            $inventoryItems = $user->inventories()->with('item')->get();
            return response()->json([
                'status' => true,
                'message' => 'Inventory Items',
                'inventoryItems' => $inventoryItems,
            ]);
        }
        return response()->json(['status' => false, 'message' => 'Token is invalid']);
    }
}
