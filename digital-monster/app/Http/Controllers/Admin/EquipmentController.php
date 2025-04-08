<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    protected $type = ['Stat', 'DigiGarden', 'DigiGate'];

    public function index()
    {
        $allEquipment = Equipment::all()->groupBy('type');
        $displayIcons = ['fa-weight', 'fa-hard-drive', 'fa-memory'];
        return view('equipment.index', ['allEquipment' => $allEquipment, 'types' => $this->type, 'icons' => $displayIcons]);
    }

    public function edit()
    {
        $materialItems = Item::where('type', 'Material')->get();
        $equipment = Equipment::find(session('equipment_id'));
        $icons = ['fa-dumbbell', 'fa-running', 'fa-shield-alt', 'fa-brain', 'fa-hard-drive', 'fa-memory'];
        $stats = ['Strength', 'Agility', 'Defense', 'Mind'];
        return view('equipment.form', ['equipment' => $equipment, 'icon' => $icons, 'stats' => $stats, 'type' => $this->type, 'materialItems' => $materialItems]);
    }

    public function update(Request $request)
    {
        $equipment = Equipment::findOrNew(session('equipment_id'));

        $validationRules = [
            'icon' => 'required|string|max:255',
            'type' => 'required|string',
            'max_level' => 'required|integer|min:1|max:5',
            'upgrade_item_id' => 'nullable|exists:items,id',
        ];

        if ($request->input('type') == 'Stat') {
            $validationRules['stat'] = 'required|string';
            if (!$equipment->image) {
                $validationRules['image'] = 'required|image|mimes:png,jpg|max:2048';
            }
        }

        $validatedData = $request->validate($validationRules);
        $equipmentData = $validatedData;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('equipment', 'public');
            $equipmentData['image'] = $path;

            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }
        }

        $equipment->fill($equipmentData);
        $equipment->save();
        $message = session('equipment_id') ? 'Equipment updated successfully.' : 'Equipment created successfully.';

        return redirect()->route('equipment.index')->with('success', $message);
    }

    public function destroy()
    {
        $equipment = Equipment::findOrFail(session('equipment_id'));
        if ($equipment->image) {
            Storage::disk('public')->delete($equipment->image);
        }
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }
}
