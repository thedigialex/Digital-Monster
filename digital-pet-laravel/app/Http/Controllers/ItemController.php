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
        $itemTypes = ["All", "Attack", "Background", "Case", "Useable"];
        return view('items.index', compact('items', 'itemTypes'));
    }

    public function handleItem(Request $request, $id = null)
    {
        $item = $id ? Item::findOrFail($id) : null;
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $this->validateItem($request, $id !== null);
            $path = $this->handleImageUpload($request);
            if ($item) {
                if ($request->hasFile('image') && $item->image) {
                    Storage::delete($item->image);
                    $item->image = $path;
                }
                $item->update([
                    'name' => $request->name,
                    'type' => $request->type,
                    'price' => $request->price,
                    'available' => $request->available,
                    'rarity' => $request->rarity, 
                ]);
                return redirect()->route('items.index')->with('success', 'Item updated successfully.');
            } else {
                $item = Item::create([
                    'name' => $request->name,
                    'image' => $path,
                    'type' => $request->type,
                    'price' => $request->price,
                    'available' => $request->available,
                    'rarity' => $request->rarity, 
                ]);

                $item->save();
                return redirect()->route('items.index')->with('success', 'Item created successfully.');
            }
        }
        return view('items.edit', compact('item'));
    }

    private function validateItem(Request $request, $isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'price' => 'required|numeric',
            'available' => 'required|numeric',
            'rarity' => 'required|string|in:free,common,uncommon,rare,legendary',
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
