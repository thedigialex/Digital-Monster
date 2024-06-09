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
                $isEquipped = $request->query('isEquipped', null);
                if ($isEquipped !== null) {
                    $isEquipped = filter_var($isEquipped, FILTER_VALIDATE_BOOLEAN);
                }

                $query = $user->inventory()->with('item');
                if ($isEquipped !== null) {
                    $query->where('is_equipped', $isEquipped);
                }

                $inventory = $query->get();

                return response()->json([
                    'status' => true,
                    'message' => 'User Inventory Retrieved Successfully',
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
