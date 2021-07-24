<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember =  $request->remember == 'on' ? true : false;

        if (Auth::attempt($credentials, $remember)) return redirect('/')->withSuccess('Sign in successful!');

        return redirect("login")->withSuccess('Email or password is not valid.');
    }
}
