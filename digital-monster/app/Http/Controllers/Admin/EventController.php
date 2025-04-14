<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Item;
use App\Models\Event;
use App\Models\UserItem;
use App\Models\UserMonster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            '4' => 'Bits',
            '5' => 'Strength',
            '6' => 'Defense',
            '7' => 'Agility',
            '8' => 'Mind',
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

    public function destroy()
    {
        $event = Event::find(session('event_id'));
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function generateStep(Request $request)
    {
        $user = User::find(Auth::id());

        $userMonster = UserMonster::with('monster')
            ->where('id', $request->user_monster_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userMonster || $userMonster->sleep_at || $userMonster->energy <= 0) {
            return response()->json([
                'message' => 'Hmmm something is off.',
                'successful' => false
            ]);
        }

        $event = Event::inRandomOrder()->first();

        if ($event->item_id) {
            $item = Item::find($event->item_id);
            $userItem = UserItem::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->first();

            if ($userItem) {
                if ($userItem->quantity < $item->max_quantity) {
                    $userItem->quantity += 1;
                    $userItem->save();
                }
            } else {
                UserItem::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'quantity' => 1,
                ]);
            }
        } else {
            switch ($event->type) {
                case 2:
                    $userMonster->losses += 1;
                    break;
                case 3:
                    $userMonster->wins += 1;
                    break;
                case 4:
                    $user->bits += rand(15, 250);
                    $user->save();
                    break;
                case 5:
                    $userMonster->strength += 1;
                    break;
                case 6:
                    $userMonster->defense += 1;
                    break;
                case 7:
                    $userMonster->agility += 1;
                    break;
                case 8:
                    $userMonster->mind += 1;
                    break;
            }
        }

        $userMonster->steps += 1;
        $userMonster->save();

        return response()->json([
            'successful' => true,
            'message' => "{$userMonster->name} " . $event->message,
            'duration' => rand(3, 6) * 1000,
        ]);
    }
}
