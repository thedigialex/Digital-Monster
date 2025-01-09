<?php

namespace App\Http\Controllers;

use App\Models\TrainingEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingEquipmentController extends Controller
{
    public function index()
    {
        $trainingEquipments = TrainingEquipment::all()->groupBy('stat');
        $stats = [
            'strength' => 'Strength',
            'agility' => 'Agility',
            'defense' => 'Defense',
            'mind' => 'Mind',
            'cleaning' => 'Cleaning',
        ];

        return view('training_equipment.index', compact('trainingEquipments', 'stats'));
    }

    public function edit(Request $request)
    {
        $trainingEquipment = TrainingEquipment::find($request->input('id'));
        $stats = [
            'strength' => 'Strength',
            'agility' => 'Agility',
            'defense' => 'Defense',
            'mind' => 'Mind',
            'cleaning' => 'Cleaning'
        ];
        return view('training_equipment.form', compact('trainingEquipment', 'stats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stat' => 'required|string|in:strength,agility,defense,mind,cleaning',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $equipmentData = $request->only(['name', 'stat']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('training_equipment_images', 'public');
            $equipmentData['image'] = $path;

            if ($request->has('id')) {
                $trainingEquipment = TrainingEquipment::findOrFail($request->input('id'));
                if ($trainingEquipment->image) {
                    Storage::delete($trainingEquipment->image);
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
            Storage::delete($trainingEquipment->image);
        }

        $trainingEquipment->delete();
        return redirect()->route('trainingEquipments.index')->with('success', 'Training Equipment deleted successfully.');
    }
}
