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
        return view('item.index', [
            'items' => $items,
            'rarityTypes' => $this->rarityTypes,
            'itemTypes' => $this->itemTypes,
            'icons' => $icons,
        ]);
    }

    public function edit()
    {
        $item = Item::find(session('item_id'));
        return view('item.form', ['item' => $item, 'rarityTypes' => $this->rarityTypes, 'itemTypes' => $this->itemTypes]);
    }

    public function update(Request $request)
    {
        $validationRules = ([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'max_quantity' => 'required|numeric|min:1',
            'available' => 'required|numeric',
            'rarity' => 'required|string',
        ]);
        if (!session('item_id')) {
            $validationRules['image'] = 'required|image|mimes:png,jpg|max:2048';
        }
        $request->validate($validationRules);

        $itemData = $request->only(['name', 'type', 'effect', 'price', 'rarity', 'available', 'max_quantity']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('item', 'public');
            $itemData['image'] = $path;

            if (session('item_id')) {
                $item = Item::findOrFail(session('item_id'));
                Storage::disk('public')->delete($item->image);
            }
        }

        $item = session('item_id') ? Item::findOrFail(session('item_id'))->update($itemData) : Item::create($itemData);
        $message = session('item_id') ? 'Item updated successfully.' : 'Item created successfully.';

        return redirect()->route('items.index')->with('success', $message);
    }

    public function destroy(Item $item)
    {
        $item = Item::findOrFail(session('item_id'));
        Storage::disk('public')->delete($item->image);
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
