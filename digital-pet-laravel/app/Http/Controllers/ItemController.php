<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

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

    private function validateItem(Request $request, $isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'available' => 'sometimes|boolean',
        ];

        if (!$isUpdate || $request->hasFile('image')) {
            $rules['image'] = 'required|image';
        }

        $request->validate($rules);
    }

    private function handleImageUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('public/items');
        }
        return null;
    }

    public function store(Request $request)
    {
        $this->validateItem($request);
        $path = $this->handleImageUpload($request);

        $item = Item::create([
            'name' => $request->name,
            'image' => $path,
            'type' => $request->type,
            'price' => $request->price,
            'available' => $request->filled('available'),
        ]);

        $item->save();

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $this->validateItem($request, true);

        if ($request->hasFile('image')) {
            Storage::delete($item->image);
            $item->image = $this->handleImageUpload($request);
        }

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
        if ($item->image) {
            Storage::delete($item->image);
        }
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
