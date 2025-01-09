<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all()->groupBy('type');
        $rarityLabels = [
            0 => 'Free',
            1 => 'Common',
            2 => 'Uncommon',
            3 => 'Rare',
            4 => 'Legendary',
            5 => 'Mythical',
        ];
        $itemTypes = config('item_types');
        return view('items.index', compact('items', 'rarityLabels', 'itemTypes'));
    }

    public function edit(Request $request)
    {    
        $item = Item::find($request->input('id'));
        $itemTypes = config('item_types');
        return view('items.form', compact('item', 'itemTypes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'effect' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'rarity' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $itemData = $request->only(['name', 'type', 'effect', 'price', 'rarity']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('item_images', 'public');
            $itemData['image'] = $path;

            if ($request->has('id')) {
                $item = Item::findOrFail($request->input('id'));
                if ($item->image) {
                    Storage::delete($item->image);
                }
            }
        }

        if ($request->has('id')) {
            $item = Item::findOrFail($request->input('id'));
            $item->update($itemData);
            $message = 'Item updated successfully.';
        } else {
            Item::create($itemData);
            $message = 'Item created successfully.';
        }

        return redirect()->route('items.index')->with('success', $message);
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::delete($item->image);
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
