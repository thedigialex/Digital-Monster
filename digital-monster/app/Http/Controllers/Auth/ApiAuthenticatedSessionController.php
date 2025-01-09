<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\DigitalMonster;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthenticatedSessionController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('API Token')->plainTextToken;
            return $this->respondWithUserData($user, $token);
        }

        return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);
        $token = $user->createToken('API Token')->plainTextToken;

        return $this->respondWithUserData($user, $token);
    }

    public function checkToken(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $token = null;
            return $this->respondWithUserData($user, $token);
        }

        return response()->json(['status' => false, 'message' => 'Token is invalid'], 401);
    }

    private function respondWithUserData($user, $token)
    {
        $mainDigitalMonster = $user->digitalMonsters()->where('isMain', true)->with('digitalMonster')->first();
        $user->mainDigitalMonster = $mainDigitalMonster;
        if (!is_null($mainDigitalMonster)) {
            $user->mainDigitalMonster->digitalMonster = $mainDigitalMonster->digitalMonster;
        }
        $inventoryItems = $user->inventories()->with('item')->get();
        $trainingEquipments = $user->trainingEquipments()->with('trainingEquipment')->get();
        $eggMonsters = is_null($mainDigitalMonster) ? DigitalMonster::where('stage', 'egg')->get() : null;
        $user->mainDigitalMonster = $mainDigitalMonster;
        $user->inventoryItems = $inventoryItems;
        $user->trainingEquipments = $trainingEquipments; 
        return response()->json([
            'status' => true,
            'message' => 'Logged in',
            'user' => $user,
            'eggMonsters' => $eggMonsters,
            'token' => $token,
        ]);
    }
}
