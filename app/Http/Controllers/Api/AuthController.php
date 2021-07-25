<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $user->createToken('Access_token')->plainTextToken,
                'user' => $user,
            ]
        ], 200);
    }

    public function google(Request $request)
    {
        $user = User::where('google_id', $request->id)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'google_id' => $request->id,
                'password' => Hash::make('123456'),
                'role_id' => Role::find(1)->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $user->createToken('Access_token')->plainTextToken,
                'user' => $user,
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::find(4)->id
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $user->createToken('Access_token')->plainTextToken,
                'user' => $user,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = User::find($request->id);
        $user->tokens()->where('id', $request->token)->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
}
