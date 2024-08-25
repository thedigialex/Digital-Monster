<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function handleItem(Request $request, $id, $itemId = null)
    {
        $user = User::findOrFail($id);
        $allItems = Item::all();
        $item = $itemId ? Inventory::findOrFail($itemId) : null;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'item_id' => 'required|exists:items,id',
                'quantity' => 'required|integer|min:1',
                'is_equipped' => 'required|boolean',
            ]);

            if ($item) {
                $item->update($validated);
                return redirect()->route('user.show', $id)->with('success', 'Inventory item updated successfully.');
            } else {
                $validated['user_id'] = $id;
                Inventory::create($validated);
                return redirect()->route('user.show', $id)->with('success', 'Inventory item created successfully.');
            }
        }
        return view('items.user-inventory-edit', compact('user', 'allItems', 'item'));
    }

    public function deleteItem($id, $itemId)
    {
        $inventory = Inventory::findOrFail($itemId);
        $inventory->delete();

        return redirect()->route('user.show', $id)->with('success', 'Inventory item deleted successfully.');
    }

    //Same function for local and API calls
    public function getUserInventory(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $isEquipped = $request->query('isEquipped', null);
                $query = $user->inventory()->with('item');
                if ($isEquipped !== null) {
                    $isEquipped = filter_var($isEquipped, FILTER_VALIDATE_BOOLEAN);
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
