<?php

namespace App\Http\Controllers;

use App\Models\DigitalMonster;
use App\Models\EggGroup;
use App\Models\EvolutionRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalMonsterController extends Controller
{
    protected $elements = ['Fire', 'Water', 'Air', 'Nature', 'Machine', 'Light', 'Dark'];
    protected $stages = ['Egg', 'Fresh', 'Child', 'Rookie', 'Champion', 'Ultimate', 'Mega'];

    public function index()
    {
        $eggGroups = EggGroup::all();
        if ($eggGroups->isEmpty()) {
            return redirect()->route('egg_groups.edit')
                ->with('error', 'No egg groups available. Create an egg groups first.')
                ->withInput();
        }
        $icons = [
            'Tyrannos' => 'fa-dragon',
            'Insecta' => 'fa-bug',
            'Beast' => 'fa-paw',
            'Flora' => 'fa-leaf',
            'Abyss' => 'fa-water',
            'Arcane' => 'fa-magic',
        ];
        return view('digital_monsters.index', ['eggGroups' => $eggGroups, 'icons' => $icons]);
    }

    public function edit(Request $request)
    {
        $digitalMonster = DigitalMonster::find(session('digital_monster_id'));
        $eggGroups = EggGroup::all();
        $allDigitalMonsters = DigitalMonster::all();
        return view('digital_monsters.form', ['digitalMonster' => $digitalMonster, 'eggGroups' => $eggGroups, 'stages' => $this->stages, 'elements' => $this->elements, 'allDigitalMonsters' => $allDigitalMonsters]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'stage' => 'required|string',
            'egg_group_id' => 'required|exists:egg_groups,id',
            'element_0' => 'required|string',
        ];

        $id = session('digital_monster_id');

        if (!$id) {
            $rules['sprite_image_0'] = 'required|image|mimes:jpg,jpeg,png,gif|max:10240';
        }

        $request->validate($rules);
        
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

        foreach (['sprite_image_0', 'sprite_image_1', 'sprite_image_2'] as $imageKey) {
            if ($request->hasFile($imageKey)) {
                $path = $request->file($imageKey)->store('spriteImages', 'public');
                $digitalMonsterData[$imageKey] = $path;

                if ($id) {
                    $digitalMonster = DigitalMonster::findOrFail($id);
                    if ($digitalMonster->$imageKey) {
                        Storage::disk('public')->delete($digitalMonster->$imageKey);
                    }
                }
            }
        }

        if ($id) {
            $digitalMonster = DigitalMonster::findOrFail($id);
            $digitalMonster->update($digitalMonsterData);
        } else {
            $digitalMonster = DigitalMonster::create($digitalMonsterData);
        }

        $routeAId = $request->input('route_a');
        $routeBId = $request->input('route_b');

        if ($id) {
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

        $message = $id ? 'Digital Monster updated successfully.' : 'Digital Monster created successfully.';
        return redirect()->route('digital_monsters.index')->with('success', $message);
    }

    public function destroy(DigitalMonster $monster)
    {
        //Does not function properly. Need to fix but may need to prevent deletion incase of user digital monsters deletion.
        foreach (['sprite_image_0', 'sprite_image_1', 'sprite_image_2'] as $imageKey) {
            if ($monster->$imageKey) {
                Storage::disk('public')->delete($monster->$imageKey);
            }
        }
        $monster->delete();
        return redirect()->route('digital_monsters.index')->with('success', 'Digital Monster deleted successfully.');
    }
}
