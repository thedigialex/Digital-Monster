<?php

namespace App\Http\Controllers;

use App\Models\EggGroup;
use Illuminate\Http\Request;

class EggGroupController extends Controller
{
    protected $fields = ['Tyrannos', 'Insecta', 'Beast', 'Flora', 'Abyss', 'Arcane'];

    public function index()
    {
        $eggGroups = EggGroup::all()->groupBy('field');
        $icons = [
            'fa-dragon',
            'fa-bug',
            'fa-paw',
            'fa-leaf',
            'fa-water',
            'fa-magic',
        ];
        return view('egg_groups.index', ['eggGroups' => $eggGroups, 'fields' => $this->fields, 'icons' => $icons]);
    }
    
    public function edit()
    {
        $eggGroup = EggGroup::find(session('egg_group_id'));
        return view('egg_groups.form', [
            'eggGroup' => $eggGroup,
            'fields' => $this->fields
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'field' => 'required|string',
        ]);

        if (session('egg_group_id')) {
            $eggGroup = EggGroup::findOrFail(session('egg_group_id'));
            $eggGroup->update($request->all());
            $message = 'Egg group updated successfully.';
        } else {
            EggGroup::create($request->all());
            $message = 'Egg group created successfully.';
        }

        return redirect()->route('egg_groups.index')->with('success', $message);
    }

    public function destroy()
    {
        $eggGroup = EggGroup::findOrFail(session('egg_group_id'));
        $eggGroup->delete();
        return redirect()->route('egg_groups.index')->with('success', 'Egg group deleted successfully.');
    }
}
