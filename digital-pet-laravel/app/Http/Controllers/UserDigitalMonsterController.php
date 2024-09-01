<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DigitalMonster;
use App\Models\UserDigitalMonster;
use Illuminate\Http\Request;

class UserDigitalMonsterController extends Controller
{
    public function handleMonster(Request $request, $id, $monsterId = null)
    {
        $user = User::findOrFail($id);
        $monstersByEgg = DigitalMonster::all()->sortBy('eggId')->sortBy('monsterId');
        $userDigitalMonster = $monsterId ? UserDigitalMonster::findOrFail($monsterId) : null;

        $monsterOptions = $monstersByEgg->mapWithKeys(fn($monster) => [
            $monster->id => 'Egg: ' . $monster->eggGroup->name . ' | Monster Id: ' . $monster->monsterId
        ]);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'digital_monster_id' => 'required|exists:digital_monsters,id',
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'isMain' => 'required|boolean'
            ]);

            $userDigitalMonster
                ? $userDigitalMonster->update($validated)
                : $user->userDigitalMonsters()->create($validated);

            return redirect()->route('user.show', $id)
                ->with('success', 'Digital Monster ' . ($userDigitalMonster ? 'updated' : 'created') . ' successfully.');
        }

        return view('digitalMonsters.user-monster-edit', compact('user', 'monsterOptions', 'userDigitalMonster'));
    }

    public function deleteMonster($id, $monsterId)
    {
        UserDigitalMonster::findOrFail($monsterId)->delete();
        return redirect()->route('user.show', $id)->with('success', 'Digital Monster deleted successfully.');
    }

    public function createUserDigitalMonster(Request $request)
    {
        $user = $request->user();
        $eggId = $request->query('eggId');
        $digitalMonster = DigitalMonster::where('eggId', $eggId)->where('monsterId', 0)->firstOrFail();
        $userDigitalMonster = $user->userDigitalMonsters()->create([
            'digital_monster_id' => $digitalMonster->id,
            'name' => 'New Monster',
            'type' => 'Data',
            'isMain' => 1,
        ]);
        $userDigitalMonster->load('digitalMonster');
        return response()->json([
            'status' => true,
            'message' => 'User Digital Monster created successfully',
            'userDigitalMonster' => $userDigitalMonster
        ], 201);
    }

    public function getUserDigitalMonster(Request $request)
    {
        return $this->findUserDigitalMonster(
            $request,
            fn($monster) => $monster
                ? response()->json(['status' => true, 'message' => 'Retrieved Successfully', 'userDigitalMonster' => $monster], 200)
                : response()->json(['status' => false, 'message' => 'No main User Digital Monster found'], 404),
            ['isMain' => 1]
        );
    }

    public function updateUserDigitalMonster(Request $request)
    {
        return $this->findUserDigitalMonster(
            $request,
            fn($monster) => $monster
                ? tap($monster)->update($request->all())->response()->json(['status' => true, 'message' => 'Updated Successfully', 'userDigitalMonster' => $monster], 200)
                : response()->json(['status' => false, 'message' => 'User Digital Monster not found'], 404),
            ['id' => $request->input('id')]
        );
    }

    private function findUserDigitalMonster(Request $request, callable $callback, array $conditions)
    {
        try {
            $user = $request->user();
            $monster = $user ? $user->userDigitalMonsters()->with('digitalMonster')->where($conditions)->first() : null;
            return $user ? $callback($monster) : response()->json(['status' => false, 'message' => 'User not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function getDigitalMonsters(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $query = match (true) {
                    $request->query('eggReturn') !== null => DigitalMonster::where('monsterId', 0),
                    $request->query('eggId') !== null && $request->query('monsterId') !== null => DigitalMonster::where('eggId', $request->query('eggId'))->where('monsterId', $request->query('monsterId')),
                    default => null
                };

                $monsters = $query?->get();
                return $monsters && $monsters->isNotEmpty()
                    ? response()->json(['status' => true, 'message' => 'Digital Monsters Retrieved Successfully', 'digitalMonsters' => $monsters], 200)
                    : response()->json(['status' => false, 'message' => 'No Digital Monsters Found'], 404);
            }

            return response()->json(['status' => false, 'message' => 'Invalid parameters'], 404);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
