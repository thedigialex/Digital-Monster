<?php

namespace App\Http\Controllers;

use App\Models\DigitalMonster;
use Illuminate\Http\Request;

class DigitalMonsterController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $monsters = $user->digitalMonsters;
        return response()->json($monsters);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'level' => 'required|integer',
        ]);

        $monster = new DigitalMonster($request->all());
        $request->user()->digitalMonsters()->save($monster);

        return response()->json($monster, 201);
    }
}
