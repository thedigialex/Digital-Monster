<?php

namespace App\Http\Controllers;

use App\Models\TrainingEquipment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingEquipmentController extends Controller
{
    protected $stats = ['Strength', 'Agility', 'Defense', 'Mind', 'Cleaning'];
    
    public function index()
    {
        $trainingEquipments = TrainingEquipment::all()->groupBy('stat');
        return view('training_equipment.index', ['trainingEquipments' => $trainingEquipments, 'stats' => $this->stats]);
    }

    public function edit(Request $request)
    {
        $materialItems = Item::where('type', 'Material')->get();
        $trainingEquipment = TrainingEquipment::find($request->input('id'));
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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('trainingEquipmentImages', 'public');
            $equipmentData['image'] = $path;

            if ($request->has('id')) {
                $trainingEquipment = TrainingEquipment::findOrFail($request->input('id'));
                if ($trainingEquipment->image) {
                    Storage::disk('public')->delete($trainingEquipment->image);
                }
            }
        }

        if ($request->has('id')) {
            $trainingEquipment = TrainingEquipment::findOrFail($request->input('id'));
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
