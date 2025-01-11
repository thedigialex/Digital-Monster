<?php

namespace App\Http\Controllers;

use App\Models\EggGroup;
use Illuminate\Http\Request;

class EggGroupController extends Controller
{
    protected $fieldTypes = ['Tyrannos', 'Insecta', 'Brute', 'Flora', 'Abyss', 'Arcane'];

    public function index()
    {
        $eggGroups = EggGroup::all()->groupBy('field_type');
        return view('egg_groups.index', ['eggGroups' => $eggGroups, 'fieldTypes' => $this->fieldTypes]);
    }

    public function edit(Request $request)
    {
        $eggGroup = EggGroup::find($request->input('id'));
        return view('egg_groups.form', ['eggGroup' => $eggGroup, 'fieldTypes' => $this->fieldTypes]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'field_type' => 'required|string',
        ]);

        if ($request->has('id')) {
            $eggGroup = EggGroup::findOrFail($request->input('id'));
            $eggGroup->update($request->all());
            $message = 'Egg group updated successfully.';
        } else {
            EggGroup::create($request->all());
            $message = 'Egg group created successfully.';
        }

        return redirect()->route('egg_groups.index')->with('success', $message);
    }

    public function destroy(EggGroup $eggGroup)
    {
        $eggGroup->delete();
        return redirect()->route('egg_groups.index')->with('success', 'Egg group deleted successfully.');
    }
}
