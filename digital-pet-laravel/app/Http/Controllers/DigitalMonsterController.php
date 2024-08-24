<?php

namespace App\Http\Controllers;

use App\Models\DigitalMonster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EggGroup;

class DigitalMonsterController extends Controller
{
    public function index(Request $request)
    {
        $digitalMonsters = DigitalMonster::all();
        $eggGroups = EggGroup::pluck('name', 'id')->toArray();
        return view('digitalmonsters.index', compact('digitalMonsters', 'eggGroups'));
    }

    public function handleMonster(Request $request, $id = null)
    {
        $digitalMonster = $id ? DigitalMonster::findOrFail($id) : null;
        $eggGroups = EggGroup::pluck('name', 'id')->toArray();

        if ($request->isMethod('post') || $request->isMethod('put')) {
            $this->validateDigitalMonster($request, $id !== null);
            $path = $this->handleSpriteUpload($request);
            $stage = $this->getStage($request->monster_id);
            $minValues = $this->getMinValues($stage);

            if ($digitalMonster) {
                if ($request->hasFile('sprite_sheet') && $digitalMonster->spriteSheet) {
                    Storage::delete($digitalMonster->spriteSheet);
                    $digitalMonster->spriteSheet = $path;
                }
                $digitalMonster->update([
                    'monsterId' => $request->monster_id,
                    'eggId' => $request->egg_id,
                    'stage' => $stage,
                    'minWeight' => $minValues[0],
                    'maxEnergy' => $minValues[1],
                    'requiredEvoPoints' => $minValues[2]
                ]);

                return redirect()->route('digitalMonsters.index')->with('success', 'Monster updated successfully.');
            } else {
                $digitalMonster = DigitalMonster::create([
                    'monsterId' => $request->monster_id,
                    'eggId' => $request->egg_id,
                    'spriteSheet' => $path,
                    'stage' => $stage,
                    'minWeight' => $minValues[0],
                    'maxEnergy' => $minValues[1],
                    'requiredEvoPoints' => $minValues[2]
                ]);

                return redirect()->route('digitalMonsters.index')->with('success', 'Monster created successfully.');
            }
        }

        $options = [];
        for($i = 0; $i <=26; $i++) {
            $options[$i] = $i;
        }

        return view('digitalmonsters.edit', compact('digitalMonster', 'eggGroups', 'options'));
    }

    private function validateDigitalMonster(Request $request, $isUpdate = false)
    {
        $rules = [
            'egg_id' => 'required|integer',
            'monster_id' => 'required|integer',
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

        return redirect()->route('digitalMonsters.index')->with('success', 'Monster deleted successfully!');
    }

    public function getMinValues($stage)
    {
        $baseValues = [5, 5, 25];
        $additionalValues = [
            "Rookie" => 5,
            "Champion" => 10,
            "Ultimate" => 15,
            "Mega" => 20,
        ];

        if (array_key_exists($stage, $additionalValues)) {
            $baseValues[0] += $additionalValues[$stage];
            $baseValues[1] += $additionalValues[$stage];
            $baseValues[2] *= ($additionalValues[$stage] + 5);
        }
        return $baseValues;
    }

    public function getStage($monster_id)
    {
        switch(true) {
            case $monster_id == 1:
                return "Fresh";
            case $monster_id == 2:
                return "Child";  
            case $monster_id == 3 || $monster_id == 4:
                return "Rookie";
            case $monster_id >= 5 && $monster_id <= 9:
                return "Champion";  
            case $monster_id >= 10 && $monster_id <= 18:
                return "Ultimate";
            case $monster_id >= 19 && $monster_id <= 26:
                return "Mega";  
            default:
                return "Egg";    
        }
    }
}
