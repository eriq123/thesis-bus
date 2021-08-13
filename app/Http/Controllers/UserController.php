<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $this->data['users'] = User::all();
        return view('admin.user.index', $this->data);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|min:6|max:255|',
            'role_id' => 'required|',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Maximum length is 255 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password should have minimum of 6 characters.',
            'password.max' => 'Password should have maximum of 255 characters.',
        ]);
    }
    public function saveRequest($users, $request)
    {
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->role_id = $request->role_id;
        $users->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $user = new User();
        $this->saveRequest($user, $request);
        return redirect()->route('users.index')->with('msg', 'Information Added!');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $user = User::find($request->id);
        $this->saveRequest($user, $request);

        return redirect()->route('users.index')->withSuccess('Updated Successfully!');
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
        return redirect()->route('users.index')->withSuccess('Deleted Successfully!');
    }
}
