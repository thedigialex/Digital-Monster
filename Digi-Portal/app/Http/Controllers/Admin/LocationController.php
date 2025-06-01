<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Item;
use App\Models\Event;
use App\Models\Location;
use App\Models\UserItem;
use App\Models\UserMonster;
use Illuminate\Http\Request;
use App\Models\UserLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('locations.index', compact('locations'));
    }

    public function edit()
    {
        $location = Location::find(session('location_id'));
        $otherLocations = $location
            ? Location::where('id', '!=', $location->id)->get()
            : Location::all();
        $events = $location ? $location->events : collect();
        return view('locations.form', compact('location', 'otherLocations', 'events'));
    }

    public function update(Request $request)
    {
        $location = Location::findOrNew(session('location_id'));

        $validationRules = [
            'name' => 'required|string|max:255',
            'unlock_steps' => 'nullable|integer|min:0',
            'unlock_location_id' => 'nullable|exists:locations,id',
            'description' => 'nullable|string|max:200',
        ];

        if (!$location->image) {
            $validationRules['image'] = 'required|image|mimes:png,jpg|max:2048';
        }

        $validatedData = $request->validate($validationRules);
        $locationData = $validatedData;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('locations', 'public');
            $locationData['image'] = $path;

            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
        }

        $location->fill($locationData);
        $location->save();

        $message = session('location_id') ? 'Location updated successfully.' : 'Location created successfully.';
        session(['location_id' => $location->id]);
        return redirect()->route('location.edit')->with('success', $message);
    }

    public function destroy()
    {
        $location = Location::find(session('location_id'));
        $location->events()->delete();
        if ($location->image) {
            Storage::disk('public')->delete($location->image);
        }
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }

    public function editEvent()
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
        return view('locations.event_form', compact('event', 'types', 'items'));
    }

    public function updateEvent(Request $request)
    {
        $event = Event::findOrNew(session('event_id'));
        $validationRules = [
            'type' => 'required|integer',
            'message' => 'required|string',
            'location_id' => 'required|exists:locations,id',
        ];

        if ($request->input('type') == 1) {
            $validationRules['item_id'] = 'required|integer';
        }

        $request->merge(['location_id' => session('location_id')]);

        $validatedData = $request->validate($validationRules);
        $eventData = $validatedData;

        $event->fill($eventData);
        $event->save();

        $message = session('event_id') ? 'Event updated successfully.' : 'Event created successfully.';

        return redirect()->route('location.edit')->with('success', $message);
    }

    public function destroyEvent()
    {
        $event = Event::find(session('event_id'));
        $event->delete();
        return redirect()->route('location.edit')->with('success', 'Event deleted successfully.');
    }

    public function editUserLocation()
    {
        $userLocation = UserLocation::find(session('user_location_id'));
        $locations = Location::all();
        return view('locations.user_form', compact('userLocation', 'locations'));
    }

    public function updateUserLocation(Request $request)
    {
        $userLocation = UserLocation::findOrNew(session('user_location_id'));
        $validationRules = [
            'location_id' => 'required|integer',
            'steps' => 'required|integer',
        ];

        $validatedData = $request->validate($validationRules);
        $userLocationData = $validatedData;

        $userLocation->user_id =  session('user_edit_id');
        
        $userLocation->fill($userLocationData);
        $userLocation->save();

        $message = session('user_location_id') ? 'Location updated successfully.' : 'Location created successfully.';

        return redirect()->route('user.profile')->with('success', $message);
    }

    public function destroyUserLocation()
    {
        $userLocation = UserLocation::find(session('user_location_id'));
        $userLocation->delete();
        return redirect()->route('user.profile')->with('success', 'Event deleted successfully.');
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

        $currentLocation = UserLocation::find($user->current_location_id);
        $currentLocation->steps += 1;
        $currentLocation->save();

        $event = $currentLocation->location->events()->inRandomOrder()->first();

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

        $message = $event->message;
        if (
            $currentLocation->location->unlock_location_id &&
            $currentLocation->steps > $currentLocation->location->unlock_steps
        ) {
            $unlockedLocationId = $currentLocation->location->unlock_location_id;
            $userId = $currentLocation->user_id;

            $alreadyUnlocked = UserLocation::where('user_id', $userId)
                ->where('location_id', $unlockedLocationId)
                ->exists();

            if (!$alreadyUnlocked) {
                UserLocation::create([
                    'user_id' => $userId,
                    'location_id' => $unlockedLocationId,
                    'steps' => 0,
                ]);
                $message .= " -New Location Unlocked-";
            }
        }

        return response()->json([
            'successful' => true,
            'message' => "{$userMonster->name} " . $message,
            'duration' => rand(3, 6) * 1000,
        ]);
    }
}
