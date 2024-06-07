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

        return view('items.user_item_edit', compact('user', 'allItems', 'item'));
    }

    public function deleteItem($id, $itemId)
    {
        $inventory = Inventory::findOrFail($itemId);
        $inventory->delete();

        return redirect()->route('user.show', $id)->with('success', 'Inventory item deleted successfully.');
    }
}
