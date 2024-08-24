<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        return view('users.user_index', compact('users'));
    }

    public function show($id = null)
    {
        if ($id === null) {
            $user = auth()->user();
        } else {
            $user = User::findOrFail($id);
        }


        $userDigitalMonsters  = $user->userDigitalMonsters;
        $inventoryItems = $user->inventory;
        $items = [];

        foreach ($inventoryItems as $inventoryItem) {
            $items[] = $inventoryItem->item;
        }
        
        $itemTypes = ["All", "Attack", "Background", "Case", "Useable"];

        return view('users.user_show', compact('user', 'itemTypes', 'items'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : $user->password,
        ]);

        return redirect()->route('user.show', $user->id)->with('success', 'User updated successfully');
    }

    public function dashboard()
    {
        $user = auth()->user();

        $inventoryController = new InventoryController();
        $request = new \Illuminate\Http\Request();
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $request->replace(['isEquipped' => true]);
        $inventoryResponse = $inventoryController->getUserInventory($request);
        $inventoryData = json_decode($inventoryResponse->getContent(), true);
        $backgroundImage = null;
        $caseImage = null;
        if ($inventoryData['status']) {
            $inventory = $inventoryData['inventory'];
            foreach ($inventory as $inventoryItem) {
                $item = $inventoryItem['item'];

                if ($item['type'] === 'Background') {
                    $backgroundImage = str_replace('public/', '', $item['image']);
                }

                if ($item['type'] === 'Case') {
                    $caseImage = str_replace('public/', '', $item['image']);
                }
            }
        } else {
            $inventory = [];
        }

        $userDigitalMonsterController = new UserDigitalMonsterController();
        $spriteSheet = null;
        $requestForMonsters = new \Illuminate\Http\Request();
        $requestForMonsters->setUserResolver(function () use ($user) {
            return $user;
        });
        $requestForMonsters->replace(['isMain' => true]);

        $userDigitalMonstersResponse = $userDigitalMonsterController->getUserDigitalMonsters($requestForMonsters);
        $userDigitalMonstersData = json_decode($userDigitalMonstersResponse->getContent(), true);

        if ($userDigitalMonstersData['status']) {
            $userDigitalMonster = !empty($userDigitalMonstersData['userDigitalMonsters'])
                ? $userDigitalMonstersData['userDigitalMonsters'][0]
                : null;

            if ($userDigitalMonster && isset($userDigitalMonster['digital_monster'])) {
                $spriteSheet = $userDigitalMonster['digital_monster']['spriteSheet'] ?? 'Sprite sheet not found.';
                $spriteSheet = str_replace('public/', '', $spriteSheet);
            }
        }

        return view('components.dashboard.dashboard', compact('user', 'backgroundImage', 'caseImage', 'spriteSheet'));
    }

    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $starterItems = Item::where('rarity', 'free')->get();

            foreach ($starterItems as $item) {
                Inventory::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'quantity' => 1,
                    'is_equipped' => true,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match our records.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $token = $user->createToken("API TOKEN")->plainTextToken;
                return $this->returnUserData($user, 'User logged in', $token);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function validateToken(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return $this->returnUserData($user, 'User logged in');
        }
        return response()->json([
            'message' => 'Token is invalid',
            'status' => false
        ], 401);
    }

    public function returnUserData(User $user, string $message, $token = null)
    {
        $response = [
            'message' => $message,
            'status' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nickname' => $user->nickname
            ]
        ];

        if ($token) {
            $response['token'] = $token;
        }

        return response()->json($response, 200);
    }

    public function updateNickname(Request $request)
    {
        $user = $request->user();
        $validate = Validator::make($request->all(), [
            'nickname' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 400);
        }

        $user->nickname = $request->nickname;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Nickname updated successfully',
        ], 200);
    }
}
