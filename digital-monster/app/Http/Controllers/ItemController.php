<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    protected $itemTypes = ['Case', 'Attack', 'Background', 'Consumable', 'Material'];
    protected $rarityTypes = ['Free', 'Common', 'Uncommon', 'Rare', 'Legendary', 'Mythical'];

    public function index()
    {
        $items = Item::all()->groupBy('type');
        $icons = [
            'Case' => 'fa-box',
            'Attack' => 'fa-bolt',
            'Background' => 'fa-image',
            'Consumable' => 'fa-utensils',
            'Material' => 'fa-cogs',
        ];
        return view('items.index', [
            'items' => $items,
            'rarityTypes' => $this->rarityTypes,
            'itemTypes' => $this->itemTypes,
            'icons' => $icons,
        ]);
    }

    public function edit(Request $request)
    {
        $item = Item::find($request->input('id'));
        return view('items.form', ['item' => $item, 'rarityTypes' => $this->rarityTypes, 'itemTypes', 'itemTypes' => $this->itemTypes]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'isAvailable' => 'required|numeric',
            'rarity' => 'required|string',
        ]);

        $itemData = $request->only(['name', 'type', 'effect', 'price', 'rarity', 'isAvailable']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('itemImages', 'public');
            $itemData['image'] = $path;

            if ($request->has('id')) {
                $item = Item::findOrFail($request->input('id'));
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
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
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
