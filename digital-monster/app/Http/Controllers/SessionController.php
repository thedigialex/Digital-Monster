<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'route' => 'required|string'
        ]);

        session([$request->model . '_id' => $request->id]);
        return redirect()->route($request->route);
    }
    
    public function clear(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'route' => 'required|string'
        ]);

        session()->forget($request->model . '_id');
        return redirect()->route($request->route);
    }
}
