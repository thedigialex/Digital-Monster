<?php

namespace App\Http\Controllers;

use App\Models\DigitalMonster;
use App\Models\EggGroup;
use App\Models\EvolutionRoute;
use Illuminate\Http\Request;

class DigitalMonsterController extends Controller
{
    public function index()
    {
        $eggGroups = EggGroup::with('digitalMonsters')->get();
        return view('digital_monsters.index', compact('eggGroups'));
    }

    public function edit(Request $request)
    {
        $digitalMonster = DigitalMonster::find($request->input('id'));
        $eggGroups = EggGroup::all();
        if ($eggGroups->isEmpty()) {
            return redirect()->route('egg_groups.edit')->with('error', 'No egg groups available. Please add egg groups first.');
        }
        $stages = [
            'Egg' => 'Egg',
            'Fresh' => 'Fresh',
            'Child' => 'Child',
            'Rookie' => 'Rookie',
            'Champion' => 'Champion',
            'Ultimate' => 'Ultimate',
            'Mega' => 'Mega'
        ];
        $elements = [
            'Free' => 'Free',
            'Fire' => 'Fire',
            'Water' => 'Water',
            'Nature' => 'Nature',
            'Machine' => 'Machine',
            'Dark' => 'Dark',
            'Light' => 'Light'
        ];
        $allDigitalMonsters = DigitalMonster::all();
        return view('digital_monsters.form', compact('digitalMonster', 'stages', 'elements', 'eggGroups', 'allDigitalMonsters'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'required|string',
            'egg_group_id' => 'required|exists:egg_groups,id',
            'element_0' => 'required|string',
            'sprite_image_0' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'route_a' => 'nullable|exists:digital_monsters,id',
            'route_b' => 'nullable|exists:digital_monsters,id',
        ]);

        $evoPointsMap = [
            'Egg' => 5,
            'Fresh' => 20,
            'Child' => 60,
            'Rookie' => 160,
            'Champion' => 400,
            'Ultimate' => 960,
            'Mega' => 0,
        ];

        $requiredEvoPoints = $evoPointsMap[$request->input('stage')];

        $digitalMonsterData = $request->only(['name', 'stage', 'egg_group_id', 'element_0', 'element_1', 'element_2']);
        $digitalMonsterData['required_evo_points'] = $requiredEvoPoints;

        if ($request->has('id')) {
            $digitalMonster = DigitalMonster::findOrFail($request->input('id'));
            $digitalMonster->update($digitalMonsterData);
        } else {
            $digitalMonster = DigitalMonster::create($digitalMonsterData);
        }

        // Handle file uploads
        if ($request->hasFile('sprite_image_0')) {
            $digitalMonster->sprite_image_0 = $request->file('sprite_image_0')->store('sprite_images', 'public');
        }
        if ($request->hasFile('sprite_image_1')) {
            $digitalMonster->sprite_image_1 = $request->file('sprite_image_1')->store('sprite_images', 'public');
        }
        if ($request->hasFile('sprite_image_2')) {
            $digitalMonster->sprite_image_2 = $request->file('sprite_image_2')->store('sprite_images', 'public');
        }

        $digitalMonster->save();
        $routeAId = $request->input('route_a');
        $routeBId = $request->input('route_b');

        if ($request->has('id')) {
            $evolutionRoute = EvolutionRoute::where('evolves_from', $digitalMonster->id)->first();
            $evolutionRoute->update([
                'route_a' => $routeAId,
                'route_b' => $routeBId,
            ]);
        } else {
            EvolutionRoute::create([
                'evolves_from' => $digitalMonster->id,
                'route_a' => $routeAId,
                'route_b' => $routeBId,
            ]);
        }

        $message = $request->has('id') ? 'Digital Monster updated successfully.' : 'Digital Monster created successfully.';
        return redirect()->route('digital_monsters.index')->with('success', $message);
    }

    public function destroy(DigitalMonster $monster)
    {
        $monster->delete();
        return redirect()->route('digital_monsters.index')->with('success', 'Digital Monster deleted successfully.');
    }
}
