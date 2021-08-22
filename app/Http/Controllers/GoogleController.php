<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private $user_default_role;
    private $user_default_password;

    public function __construct()
    {
        $this->user_default_role = env('USER_DEFAULT_ROLE', 4);
        $this->user_default_password = env('USER_DEFAULT_PASSWORD', '123456');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $googleUser->id)->first();

        if (!$user) {
            $user = User::where('email', $googleUser->email)->first();

            if($user){
                $user->google_id = $googleUser->id;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make($this->user_default_password),
                    'role_id' => $this->user_default_role,
                ]);
            }
        }
        Auth::login($user);

        return redirect('/');
    }
}
