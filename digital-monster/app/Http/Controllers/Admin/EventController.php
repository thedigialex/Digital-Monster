<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    public function edit()
    {
        $event = Event::find(session('event_id'));
        $types = [
            '0' => 'Basic',
            '1' => 'Item',
            '2' => 'Battle Lose',
            '3' => 'Battle Win',
        ];
        $items = Item::all()->pluck('name', 'id');
        return view('events.form', compact('event', 'types', 'items'));
    }

    public function update(Request $request)
    {
        $event = Event::findOrNew(session('event_id'));

        $validationRules = ([
            'type' => 'required|integer',
            'message' => 'required|string',
        ]);

        if ($request->input('type') == 1) {
            $validationRules['item_id'] = 'required|integer';
        }
        
        $validatedData = $request->validate($validationRules);
        $eventData = $validatedData;

        $event->fill($eventData);
        $event->save();

        $message = session('event_id') ? 'Event updated successfully.' : 'Event created successfully.';

        return redirect()->route('events.index')->with('success', $message);
    }

    public function destroy(Item $item)
    {
        $event = Event::find(session('event_id'));
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
