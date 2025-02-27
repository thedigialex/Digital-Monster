<?php

namespace App\Http\Controllers;

use App\Models\TrainingEquipment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingEquipmentController extends Controller
{
    protected $stats = ['Strength', 'Agility', 'Defense', 'Mind', 'Cleaning', 'Lighting'];

    public function index()
    {
        $trainingEquipments = TrainingEquipment::all()->groupBy('stat');
        $icons = [
            'fa-dumbbell',
            'fa-running',
            'fa-shield-alt',
            'fa-brain',
            'fa-soap',
            'fa-lightbulb',
        ];
        return view('training_equipment.index', ['trainingEquipments' => $trainingEquipments, 'stats' => $this->stats, 'icons' => $icons]);
    }

    public function edit()
    {
        $materialItems = Item::where('type', 'Material')->get();
        $trainingEquipment = TrainingEquipment::find(session('training_equipment_id'));
        return view('training_equipment.form', ['trainingEquipment' => $trainingEquipment, 'stats' => $this->stats, 'materialItems' => $materialItems]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stat' => 'required|string',
            'max_level' => 'required|integer|min:1|max:5',
        ]);

        $equipmentData = $request->only(['name', 'stat', 'max_level', 'upgrade_item_id']);
        
        $id = session('training_equipment_id');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('trainingEquipmentImages', 'public');
            $equipmentData['image'] = $path;

            if ($id) {
                $trainingEquipment = TrainingEquipment::findOrFail($id);
                if ($trainingEquipment->image) {
                    Storage::disk('public')->delete($trainingEquipment->image);
                }
            }
        }

        if ($id) {
            $trainingEquipment = TrainingEquipment::findOrFail($id);
            $trainingEquipment->update($equipmentData);
            $message = 'Training Equipment updated successfully.';
        } else {
            TrainingEquipment::create($equipmentData);
            $message = 'Training Equipment created successfully.';
        }

        return redirect()->route('trainingEquipments.index')->with('success', $message);
    }

    public function destroy(TrainingEquipment $trainingEquipment)
    {
        if ($trainingEquipment->image) {
            Storage::disk('public')->delete($trainingEquipment->image);
        }

        $trainingEquipment->delete();
        return redirect()->route('trainingEquipments.index')->with('success', 'Training Equipment deleted successfully.');
    }
}
