<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRepository
{
    private function saveRequest($user, $request)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();
    }

    private function validateRequest($request, $isUpdate = false)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'role_id' => 'required',
        ];

        $errorMessages = [
            'name.required' => 'Name is required.',
            'name.max' => 'Maximum length is 255 characters.',
            'email.required' => 'Email is required.',
            'email.max' => 'Maximum length is 255 characters.',
            'role_id.required' => 'Role is required.',
        ];

        if ($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $this->validateRequest($request);
        $user = new User();
        $user->password = bcrypt(env('USER_DEFAULT_PASSWORD'));
        $this->saveRequest($user, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $user)
    {
        $this->validateRequest($request, true);
        $this->saveRequest($user, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
    }

    public function validatePassword($request)
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6|different:old_password',
            'new_password_confirmation' => 'required'
        ];

        Validator::make($request->all(), $rules)->validate();
    }

    public function changePassword($request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $user;

    }
}
