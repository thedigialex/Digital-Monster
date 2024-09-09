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

            $evolutionRoutes = $request->has('evolution_routes') ? json_encode($request->evolution_routes) : null;

            if ($digitalMonster) {
                if ($request->hasFile('sprite_sheet') && $digitalMonster->spriteSheet) {
                    Storage::delete($digitalMonster->spriteSheet);
                    $digitalMonster->spriteSheet = $path;
                }

                $digitalMonster->update([
                    'monsterId' => $request->monster_id,
                    'eggId' => $request->egg_id,
                    'stage' => $stage,
                    'requiredEvoPoints' => $minValues[0],
                    'evolution_routes' => $evolutionRoutes, // Handle evolution routes
                ]);

                return redirect()->route('digitalMonsters.index')->with('success', 'Monster updated successfully.');
            } else {
                $digitalMonster = DigitalMonster::create([
                    'monsterId' => $request->monster_id,
                    'eggId' => $request->egg_id,
                    'spriteSheet' => $path,
                    'stage' => $stage,
                    'requiredEvoPoints' => $minValues[0],
                    'evolution_routes' => $evolutionRoutes, // Handle evolution routes
                ]);

                return redirect()->route('digitalMonsters.index')->with('success', 'Monster created successfully.');
            }
        }

        $options = [];
        for ($i = 0; $i <= 26; $i++) {
            $options[$i] = match (true) {
                $i == 1 => "Fresh",
                $i == 2 => "Child",
                $i == 3, $i == 4 => "Rookie",
                $i >= 5 && $i <= 9 => "Champion",
                $i >= 10 && $i <= 18 => "Ultimate",
                $i >= 19 && $i <= 26 => "Mega",
                default => "Egg",
            };
        }

        return view('digitalmonsters.edit', compact('digitalMonster', 'eggGroups', 'options'));
    }

    private function validateDigitalMonster(Request $request, $isUpdate = false)
    {
        $rules = [
            'egg_id' => 'required|integer',
            'monster_id' => 'required|integer',
            'evolution_routes' => 'nullable|array', // Validate evolution routes as an array
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
            $baseValues[0] *= ($additionalValues[$stage] + 5);
        }
        return $baseValues;
    }

    public function getStage($monster_id)
    {
        switch (true) {
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

    public function evolveUserDigitalMonster(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $userDigitalMonster = $user->userDigitalMonsters()
                    ->with('digitalMonster')
                    ->where('isMain', 1)
                    ->first();

                if (!$userDigitalMonster || !$userDigitalMonster->digitalMonster) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No main Digital Monster found for evolution.'
                    ], 404);
                }

                $evolutionRoutes = json_decode($userDigitalMonster->digitalMonster->evolution_routes, true);

                if (empty($evolutionRoutes)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Evolution is not possible. No evolution routes available.'
                    ], 400);
                }

                if (count($evolutionRoutes) > 1) {
                    $strength = $userDigitalMonster->strength;
                    $mind = $userDigitalMonster->mind;

                    $selectedRoute = ($strength >= $mind) ? $evolutionRoutes[0] : $evolutionRoutes[1];
                } else {
                    $selectedRoute = $evolutionRoutes[0];
                }

                $newDigitalMonster = DigitalMonster::where('monsterId', $selectedRoute)->first();

                if (!$newDigitalMonster) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Evolution route is invalid. No such Digital Monster found.'
                    ], 404);
                }

                $userDigitalMonster->update([
                    'digital_monster_id' => $newDigitalMonster->id,

                ]);

                $userDigitalMonster->load('digitalMonster');

                return response()->json([
                    'status' => true,
                    'message' => 'Evolution successful. Digital Monster evolved.',
                    'userDigitalMonster' => $userDigitalMonster 
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $th->getMessage()
            ], 500);
        }
    }
}
