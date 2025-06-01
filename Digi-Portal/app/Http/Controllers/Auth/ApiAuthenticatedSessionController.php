<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthenticatedSessionController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::find(Auth::id());
            return $this->sendUserData($user);
        }
        return response()->json(['status' => false, 'message' => 'Invalid credentials']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email|unique:users,email|max:255']);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Email Already Registerd.'
            ]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        event(new Registered($user));
        Auth::login($user);
        return $this->sendUserData($user);
    }

    public function validateToken(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return $this->sendUserData($user);
        }
        return response()->json(['status' => false, 'message' => 'Token is invalid']);
    }

    private function sendUserData($user)
    {
        $user->refresh();
        $user->tokens()->delete();
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => 'Successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }
}
