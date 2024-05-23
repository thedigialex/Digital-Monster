<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.item_index', compact('items'));
    }

    public function create()
    {
        return view('items.item_edit');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items.item_edit', compact('item'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'available' => 'sometimes|boolean', 
        ]);

        $item = new Item([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'available' => $request->filled('available'),
        ]);
        $item->save(); 

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'available' => 'sometimes|boolean', 
        ]);

        $item = Item::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'available' => $request->filled('available'),
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }


    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
