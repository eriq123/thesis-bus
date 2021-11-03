<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthRepository
{
    private $user_default_role;
    private $user_default_password;

    public function __construct()
    {
        $this->user_default_role = env('USER_DEFAULT_ROLE', 4);
        $this->user_default_password = env('USER_DEFAULT_PASSWORD', '123456');
    }

    private function validateRegistration($request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
        Validator::make($request->all(), $rules)->validate();
    }

    public function createUser($request, $isGoogle = false)
    {
        if ($isGoogle) {
            $user['google_id'] = $request->id;
            $password = $this->user_default_password;
        } else {
            $password = $request->password;
        }

        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => $this->user_default_role,
            'status' => "free",
        ];

        return  User::create($user);
    }

    public function register($request)
    {
        $this->validateRegistration($request);

        return $this->createUser($request);
    }

    public function google($request)
    {
        $user = User::where('google_id', $request->id)->first();
        if (!$user) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $user->google_id = $request->id;
                $user->save();
            } else {
                $user = $this->createUser($request, true);
            }
        }

        return $user;
    }
}
