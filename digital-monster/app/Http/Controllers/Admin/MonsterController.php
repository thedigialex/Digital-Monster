<?php

namespace App\Http\Controllers\Admin;

use App\Models\Monster;
use App\Models\EggGroup;
use App\Models\Evolution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MonsterController extends Controller
{
    protected $elements = ['Fire', 'Water', 'Nature', 'Machine', 'Light', 'Dark'];
    protected $stages = ['Egg', 'Fresh', 'Child', 'Rookie', 'Champion', 'Ultimate', 'Mega'];

    public function index()
    {
        $eggGroups = EggGroup::with(['monsters' => function ($query) {
            $query->orderByRaw("FIELD(stage, 'Egg', 'Fresh', 'Child', 'Rookie', 'Champion', 'Ultimate', 'Mega')");
        }])->get();

        if ($eggGroups->isEmpty()) {
            return redirect()->route('egg_group.edit')
                ->with('error', 'No egg groups available. Create an egg group first.');
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
        $eggGroups = EggGroup::all();
        $monster = Monster::find(session('monster_id'));
        $allMonsters = Monster::all();
        return view('monsters.form', ['monster' => $monster, 'eggGroups' => $eggGroups, 'stages' => $this->stages, 'elements' => $this->elements, 'allMonsters' => $allMonsters]);
    }

    public function update(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'stage' => 'required|string',
            'egg_group_id' => 'required|exists:egg_groups,id',
            'element_0' => 'required|string',
        ];
        if (!session('monster_id')) {
            $validationRules['image_0'] = 'required|image|mimes:png,jpg|max:2048';
        }

        $request->validate($validationRules);

        $monster = Monster::find(session('monster_id'));

        $monsterData = $request->only(['name', 'stage', 'egg_group_id', 'element_0', 'element_1', 'element_2']);
        foreach (['image_0', 'image_1', 'image_2'] as $image) {
            if ($request->hasFile($image)) {
                $monsterData[$image] = $request->file($image)->store('monsters', 'public');

                if ($monster && $monster->$image) {
                    Storage::disk('public')->delete($monster->$image);
                }
            }
        }
        $evoMap = [
            'Egg' => 0,
            'Fresh' => 20,
            'Child' => 60,
            'Rookie' => 160,
            'Champion' => 400,
            'Ultimate' => 960,
            'Mega' => 0,
        ];
        $evoRequirement = $evoMap[$request->input('stage')];
        $monsterData['evo_requirement'] = $evoRequirement;

        $routeZero = $request->input('route_0');
        $routeOne = $request->input('route_1');
        if ($monster) {
            $monster->update($monsterData);
            $evolutionRoute = Evolution::where('base_monster_id', $monster->id)->first();
            $evolutionRoute->update([
                'route_0' => $routeZero,
                'route_1' => $routeOne,
            ]);
        } else {
            $monster = Monster::create($monsterData);
            Evolution::create([
                'base_monster_id' => $monster->id,
                'route_0' => $routeZero,
                'route_1' => $routeOne,
            ]);
        }

        $message = session('monster_id') ? 'Monster updated successfully.' : 'Monster created successfully.';
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
        return redirect()->route('monsters.index')->with('success', 'Monster deleted successfully.');
    }
}
