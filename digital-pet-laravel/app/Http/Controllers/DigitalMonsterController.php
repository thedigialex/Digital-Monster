<?php

namespace App\Http\Controllers;

use App\Models\DigitalMonster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalMonsterController extends Controller
{
    public function user(Request $request)
    {
        $user = $request->user();
        $monsters = $user->digitalMonsters;
        return response()->json($monsters);
    }

    public function index(Request $request)
    {
        $sort = $request->input('sort', 'monster_id');
        $direction = $request->input('direction', 'asc');

        $monsters = DigitalMonster::orderBy($sort, $direction)->get();
        return view('monsters.monster_index', compact('monsters'));
    }

    public function destroy($id)
    {
        $digitalMonster = DigitalMonster::findOrFail($id);
        if ($digitalMonster->sprite_sheet) {
            Storage::delete($digitalMonster->sprite_sheet);
        }
        $digitalMonster->delete();

        return redirect()->route('monsters.index')->with('success', 'Monster deleted successfully!');
    }

    public function edit($id)
    {
        $digitalMonster = DigitalMonster::findOrFail($id);
        return view('monsters.monster_edit', compact('digitalMonster'));
    }

    public function create()
    {
        return view('monsters.monster_edit');
    }

    private function validateDigitalMonster(Request $request, $isUpdate = false)
    {
        $rules = [
            'egg_id' => 'required|integer',
            'monster_id' => 'required|integer',
            'stage' => 'required|string',
            'type' => 'required|string',
            'min_weight' => 'required|integer',
            'max_energy' => 'required|integer',
            'required_evo_points' => 'required|integer',
        ];

        if (!$isUpdate || $request->hasFile('sprite_sheet')) {
            $rules['sprite_sheet'] = 'required|image';
        }

        $request->validate($rules);
    }


    private function handleSpriteUpload(Request $request)
    {
        if ($request->hasFile('sprite_sheet')) {
            return $request->file('sprite_sheet')->store('public/sprites');
        }
        return null;
    }

    public function store(Request $request)
    {
        $this->validateDigitalMonster($request);
        $path = $this->handleSpriteUpload($request);

        $digitalMonster = DigitalMonster::create([
            'monster_id' => $request->monster_id,
            'egg_id' => $request->egg_id,
            'sprite_sheet' => $path,
            'stage' => $request->stage,
            'type' => $request->type,
            'min_weight' => $request->min_weight,
            'max_energy' => $request->max_energy,
            'required_evo_points' => $request->required_evo_points
        ]);

        $digitalMonster->save();

        return redirect()->route('monsters.index')->with('success', 'Monster created successfully!');
    }

    public function update(Request $request, $id)
    {
        $digitalMonster = DigitalMonster::findOrFail($id);
        $this->validateDigitalMonster($request, true);

        if ($request->hasFile('sprite_sheet')) {
            $digitalMonster->sprite_sheet = $this->handleSpriteUpload($request);
        }

        $digitalMonster->update([
            'monster_id' => $request->monster_id,
            'egg_id' => $request->egg_id,
            'stage' => $request->stage,
            'type' => $request->type,
            'min_weight' => $request->min_weight,
            'max_energy' => $request->max_energy,
            'required_evo_points' => $request->required_evo_points
        ]);

        return redirect()->route('monsters.index')->with('success', 'Monster created successfully!');
    }
}
