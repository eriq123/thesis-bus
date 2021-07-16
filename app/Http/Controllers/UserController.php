<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $this->data['users'] = User::all();
        return view('pages.users.index', $this->data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|min:6|max:255|',
            'role_name' => 'required|',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Maximum length is 255 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password should have minimum of 6 characters.',
            'password.max' => 'Password should have maximum of 255 characters.',

        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role_name = $request->role_name;

        if ($request->role_name = "Bus Driver") {
            $user->roles = 2;
        } elseif ($request->role_name = "Conductor") {
            $user->roles = 3;
        } elseif ($request->role_name = "Admin") {
            $user->roles = 1;
        }

        $user->save();

        return redirect('/users')->with('msg', 'Information Added!');
    }

    public function destroy(user $user)
    {
        $user->delete();
        return redirect('/users')->with('delete', 'Information Deleted!');
    }
}
