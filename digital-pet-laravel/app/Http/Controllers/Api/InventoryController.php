<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function getUserInventory(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $inventory = $user->inventory()->with('item')->get();
                return response()->json([
                    'status' => true,
                    'message' => 'User Inventroy Retrieved Successfully',
                    'inventory' => $inventory
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
