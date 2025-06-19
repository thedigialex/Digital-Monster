<?php

namespace App\Http\Controllers\Admin;

use App\Models\EggGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EggGroupController extends Controller
{
    protected $fields = ['Tyrannos', 'Insecta', 'Beast', 'Flora', 'Abyss', 'Arcane'];

    public function index()
    {
        $eggGroupsByField = EggGroup::all()->groupBy('field');

        $fieldIcons = [
            'Tyrannos' => 'fa-dragon',
            'Insecta' => 'fa-bug',
            'Beast' => 'fa-paw',
            'Flora' => 'fa-leaf',
            'Abyss' => 'fa-water',
            'Arcane' => 'fa-magic',
        ];

        $flatGroups = collect();
        foreach ($eggGroupsByField as $field => $groups) {
            foreach ($groups as $group) {
                $group->field = $field;
                $group->icon = $fieldIcons[$field] ?? 'fa-egg';
                $flatGroups->push($group);
            }
        }

        return view('egg_groups.index', [
            'eggGroups' => $flatGroups,
        ]);
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
