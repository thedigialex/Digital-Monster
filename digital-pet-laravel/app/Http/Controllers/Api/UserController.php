<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
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

    public function getUserInventories(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $inventories = $user->inventories()->with('item')->get();

                return response()->json([
                    'status' => true,
                    'message' => 'User Inventories Retrieved Successfully',
                    'inventories' => $inventories
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
