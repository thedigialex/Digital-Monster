<?php

namespace App\Http\Controllers;

use App\Models\Monster;
use App\Models\EggGroup;
use App\Models\Evolution;
use App\Models\EvolutionRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MonsterController extends Controller
{
    protected $elements = ['Fire', 'Water', 'Air', 'Nature', 'Machine', 'Light', 'Dark'];
    protected $stages = ['Egg', 'Fresh', 'Child', 'Rookie', 'Champion', 'Ultimate', 'Mega'];

    public function index()
    {
        $eggGroups = EggGroup::all();
        if ($eggGroups->isEmpty()) {
            return redirect()->route('egg_groups.edit')
                ->with('error', 'No egg groups available. Create an egg groups first.');
        }
        $icons = [
            'Tyrannos' => 'fa-dragon',
            'Insecta' => 'fa-bug',
            'Beast' => 'fa-paw',
            'Flora' => 'fa-leaf',
            'Abyss' => 'fa-water',
            'Arcane' => 'fa-magic',
        ];
        return view('monsters.index', ['eggGroups' => $eggGroups, 'icons' => $icons]);
    }

    public function edit()
    {
        $monster = Monster::find(session('monster_id'));
        $eggGroups = EggGroup::all();
        $allMonsters = Monster::all();
        return view('monsters.form', ['monster' => $monster, 'eggGroups' => $eggGroups, 'stages' => $this->stages, 'elements' => $this->elements, 'allMonsters' => $allMonsters]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'stage' => 'required|string',
            'egg_group_id' => 'required|exists:egg_groups,id',
            'element_0' => 'required|string',
        ];

        $id = session('monster_id');

        if (!$id) {
            $rules['image_0'] = 'required|image|mimes:jpg,jpeg,png|max:10240';
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

        $monsterData = $request->only(['name', 'stage', 'egg_group_id', 'element_0', 'element_1', 'element_2']);
        $monsterData['evo_requirement'] = $requiredEvoPoints;

        foreach (['image_0', 'image_1', 'image_2'] as $imageKey) {
            if ($request->hasFile($imageKey)) {
                $path = $request->file($imageKey)->store('monsters', 'public');
                $monsterData[$imageKey] = $path;

                if ($id) {
                    $monster = Monster::findOrFail($id);
                    if ($monster->$imageKey) {
                        Storage::disk('public')->delete($monster->$imageKey);
                    }
                }
            }
        }

        if ($id) {
            $monster = Monster::findOrFail($id);
            $monster->update($monsterData);
        } else {
            $monster = Monster::create($monsterData);
        }

        $routeAId = $request->input('route_a');
        $routeBId = $request->input('route_b');

        if ($id) {
            $evolutionRoute = Evolution::where('base_monster', $monster->id)->first();
            $evolutionRoute->update([
                'route_0' => $routeAId,
                'route_1' => $routeBId,
            ]);
        } else {
            Evolution::create([
                'base_monster' => $monster->id,
                'route_0' => $routeAId,
                'route_1' => $routeBId,
            ]);
        }

        $message = $id ? 'Monster updated successfully.' : 'Monster created successfully.';
        return redirect()->route('monsters.index')->with('success', $message);
    }

    public function destroy()
    {
        $monster = Monster::findOrFail(session('monster_id'));
        foreach (['image_0', 'image_1', 'image_2'] as $imageKey) {
            if ($monster->$imageKey) {
                Storage::disk('public')->delete($monster->$imageKey);
            }
        }
        $monster->delete();
        return redirect()->route('monsters.index')->with('success', 'Digital Monster deleted successfully.');
    }
}
