<?php

namespace App\Http\Controllers;

use App\Models\EggGroup;
use Illuminate\Http\Request;

class EggGroupController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $eggGroups = EggGroup::all();
        $eggGroup = $id ? EggGroup::findOrFail($id) : null;
        return view('eggGroups.index', compact('eggGroups', 'eggGroup'));
    }


    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        EggGroup::create($request->all());
        return redirect()->route('eggGroups.index')->with('success', 'Egg Group created successfully.');
    }

    public function update(Request $request, EggGroup $eggGroup)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $eggGroup->update($request->all());
        return redirect()->route('eggGroups.index')->with('success', 'Egg Group updated successfully.');
    }

    public function destroy(EggGroup $eggGroup)
    {
        $eggGroup->delete();
        return redirect()->route('eggGroups.index')->with('success', 'Egg Group deleted successfully.');
    }
}
