<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    protected $itemTypes = ['Attack', 'Background', 'Consumable', 'Material'];
    protected $rarityTypes = ['Common', 'Uncommon', 'Rare', 'Legendary'];

    public function index()
    {
        $items = Item::all()->groupBy('type');
        $icons = [
            'Attack' => 'fa-bolt',
            'Background' => 'fa-image',
            'Consumable' => 'fa-drumstick-bite',
            'Material' => 'fa-boxes-stacked',
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
        $item = Item::findOrNew(session('item_id'));

        $validationRules = ([
            'type' => 'required|string',
            'rarity' => 'required|string',
            'available' => 'required|numeric',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_quantity' => 'required|numeric|min:1',
        ]);

        if (!$item->image) {
            $validationRules['image'] = 'required|image|mimes:png,jpg|max:2048';
        }

        if ($request->input('type') == 'Background') {
            if (!$item->image_1) {
                $validationRules['image_1'] = 'required|image|mimes:png,jpg|max:2048';
            }
            if (!$item->image_2) {
                $validationRules['image_2'] = 'required|image|mimes:png,jpg|max:2048';
            }
        }

        if ($request->input('type') == 'Consumable') {
            $validationRules['effect'] = 'required|string';
        }

        $validatedData = $request->validate($validationRules);
        $itemData = $validatedData;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $itemData['image'] = $path;

            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
        }

        if ($request->hasFile('image_1')) {
            $path = $request->file('image_1')->store('items', 'public');
            $itemData['image_1'] = $path;

            if ($item->image_1) {
                Storage::disk('public')->delete($item->image_1);
            }
        }

        if ($request->hasFile('image_2')) {
            $path = $request->file('image_2')->store('items', 'public');
            $itemData['image_2'] = $path;

            if ($item->image_2) {
                Storage::disk('public')->delete($item->image_2);
            }
        }

        $item->fill($itemData);
        $item->save();

        $message = session('item_id') ? 'Item updated successfully.' : 'Item created successfully.';

        return redirect()->route('items.index')->with('success', $message);
    }

    public function destroy()
    {
        $item = Item::findOrFail(session('item_id'));
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        if ($item->image_1) {
            Storage::disk('public')->delete($item->image_1);
        }
        if ($item->image_2) {
            Storage::disk('public')->delete($item->image_2);
        }
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}