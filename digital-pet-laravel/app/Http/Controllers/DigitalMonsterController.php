<?php

namespace App\Http\Controllers;

use App\Models\DigitalMonster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalMonsterController extends Controller
{
    public function index(Request $request)
    {
        $monsters = DigitalMonster::orderBy('monster_id', 'asc')->get();
        $groupedMonsters = $monsters->groupBy('egg_id');
        return view('monsters.monster_index', compact('groupedMonsters'));
    }

    public function handleMonster(Request $request, $id = null)
    {
        $digitalMonster = $id ? DigitalMonster::findOrFail($id) : null;
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $this->validateDigitalMonster($request, $id !== null);
            $path = $this->handleSpriteUpload($request);
            if ($digitalMonster) {
                if ($request->hasFile('sprite_sheet') && $digitalMonster->sprite_sheet) {
                    Storage::delete($digitalMonster->sprite_sheet);
                    $digitalMonster->sprite_sheet = $path;
                }
                $minValues = $this->getMinValues($request->stage);
                $digitalMonster->update([
                    'monster_id' => $request->monster_id,
                    'egg_id' => $request->egg_id,
                    'stage' => $request->stage,
                    'min_weight' => $minValues[0],
                    'max_energy' => $minValues[1],
                    'required_evo_points' => $minValues[2]
                ]);
                return redirect()->route('monsters.index')->with('success', 'Monster updated successfully.');
            } else {
                $minValues = $this->getMinValues($request->stage);
                $digitalMonster = DigitalMonster::create([
                    'monster_id' => $request->monster_id,
                    'egg_id' => $request->egg_id,
                    'sprite_sheet' => $path,
                    'stage' => $request->stage,
                    'min_weight' => $minValues[0],
                    'max_energy' => $minValues[1],
                    'required_evo_points' => $minValues[2]
                ]);

                $digitalMonster->save();
                return redirect()->route('monsters.index')->with('success', 'Monster created successfully.');
            }
        }
        return view('monsters.monster_edit', compact('digitalMonster'));
    }

    private function validateDigitalMonster(Request $request, $isUpdate = false)
    {
        $rules = [
            'egg_id' => 'required|integer',
            'monster_id' => 'required|integer',
            'stage' => 'required|string',
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

    public function destroy($id)
    {
        $digitalMonster = DigitalMonster::findOrFail($id);
        if ($digitalMonster->sprite_sheet) {
            Storage::delete($digitalMonster->sprite_sheet);
        }
        $digitalMonster->delete();

        return redirect()->route('monsters.index')->with('success', 'Monster deleted successfully!');
    }

    public function getMinValues($stage)
    {
        $baseValues = [5, 5, 25];
        $additionalValues = [
            "Rookie" => 5,
            "Champion" => 10,
            "Ultimate" => 15,
            "Final" => 20,
        ];

        if (array_key_exists($stage, $additionalValues)) {
            $baseValues[0] += $additionalValues[$stage];
            $baseValues[1] += $additionalValues[$stage];
            $baseValues[2] *= ($additionalValues[$stage] + 5);
        }
        return $baseValues;
    }
}
