<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
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

        return redirect()->back()->withErrors('Email or password is incorrect.');
    }

    public function register(Request $request)
    {
        $user = $this->authRepository->register($request);
        Auth::login($user);

        return redirect("/")->withSuccess('Sign up successful!');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = $this->authRepository->google($googleUser);

        Auth::login($user);

        return redirect('/');
    }
}
