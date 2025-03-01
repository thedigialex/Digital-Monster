<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    protected $stats = ['Strength', 'Agility', 'Defense', 'Mind', 'Cleaning', 'Lighting'];
    protected $icons = ['fa-dumbbell', 'fa-running', 'fa-shield-alt', 'fa-brain', 'fa-soap', 'fa-lightbulb'];

    public function index()
    {
        $allEquipment = Equipment::all()->groupBy('stat');
        return view('equipment.index', ['allEquipment' => $allEquipment, 'stats' => $this->stats, 'icons' => $this->icons]);
    }

    public function edit()
    {
        $materialItems = Item::where('type', 'Material')->get();
        $equipment = Equipment::find(session('equipment_id'));
        return view('equipment.form', ['equipment' => $equipment, 'stats' => $this->stats, 'materialItems' => $materialItems]);
    }

    public function update(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'stat' => 'required|string',
            'max_level' => 'required|integer|min:1|max:5',
        ];
        if (!session('equipment_id')) {
            $validationRules['image'] = 'required|image|mimes:png,jpg|max:2048';
        }
        $request->validate($validationRules);

        $equipmentData = $request->only(['name', 'stat', 'max_level', 'upgrade_item_id']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('equipment', 'public');
            $equipmentData['image'] = $path;

            if (session('equipment_id')) {
                $equipment = Equipment::findOrFail(session('equipment_id'));
                Storage::disk('public')->delete($equipment->image);
            }
        }

        $equipment = session('equipment_id') ? Equipment::findOrFail(session('equipment_id'))->update($equipmentData) : Equipment::create($equipmentData);
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
