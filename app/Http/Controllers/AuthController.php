<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $user_default_role;

    public function __construct()
    {
        $this->user_default_role = env('USER_DEFAULT_ROLE', 4);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember =  $request->remember == 'on' ? true : false;

        if (Auth::attempt($credentials, $remember)) return redirect('/')->withSuccess('Sign in successful!');

        return redirect("login")->withErrors('Email or password is not valid.');
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
            'role_id' => $this->user_default_role,
        ]);

        Auth::login($user);

        return redirect("/")->withSuccess('Sign up successful!');
    }
}
