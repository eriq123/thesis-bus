<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $userByGoogleId = User::where('google_id', $user->id)->first();

        if ($userByGoogleId) {
            Auth::login($userByGoogleId);
            return redirect('/');
        } else {
            $role = Role::all();
            $user = User::create([
                'first_name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => encrypt('123456'),
                'role_id' => $role->first()->id,
            ]);

            Auth::login($user);
            return redirect('/');
        }
    }
}
