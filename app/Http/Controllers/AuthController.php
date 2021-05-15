<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken; // generate token

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->name,
                'token' => $token
            ], 200);

        }

        return response()->json([
            'message' => 'Invalid login details'
        ], 400);
    }

    public function auth_user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->name
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // delete all token
        return response()->json([
            'message' => 'you are logged out'
        ], 200);
    }
}
