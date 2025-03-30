<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    protected $stats = ['Strength', 'Agility', 'Defense', 'Mind'];
    protected $type = ['Stat', 'DigiGarden', 'DigiGate'];
    protected $icons = ['fa-dumbbell', 'fa-running', 'fa-shield-alt', 'fa-brain', 'fa-soap', 'fa-lightbulb'];

    public function index()
    {
        $allEquipment = Equipment::all()->groupBy('type');
        $displayIcons = ['fa-brain', 'fa-soap', 'fa-lightbulb'];
        return view('equipment.index', ['allEquipment' => $allEquipment, 'types' => $this->type, 'icons' => $displayIcons]);
    }

    public function edit()
    {
        $materialItems = Item::where('type', 'Material')->get();
        $equipment = Equipment::find(session('equipment_id'));
        return view('equipment.form', ['equipment' => $equipment, 'icon' => $this->icons, 'stats' => $this->stats, 'type' => $this->type, 'materialItems' => $materialItems]);
    }

    public function update(Request $request)
    {
        $equipment = Equipment::findOrNew(session('equipment_id'));

        $validationRules = [
            'icon' => 'required|string|max:255',
            'type' => 'required|string',
            'max_level' => 'required|integer|min:1|max:5',
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
        Storage::disk('public')->delete($equipment->image);
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }
}
