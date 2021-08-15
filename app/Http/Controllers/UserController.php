<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $this->data['users'] = User::with('role')->get();
        $this->data['roles'] = Role::orderByDesc('name')->get();
        return view('admin.user.index', $this->data);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'role_id' => 'required',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Maximum length is 255 characters.',
            'email.required' => 'Email is required.',
            'email.max' => 'Maximum length is 255 characters.',
            'role_id.required' => 'Role is required.',
        ]);
    }
    public function saveRequest($users, $request)
    {
        $users->name = $request->name;
        $users->email = $request->email;
        $users->role_id = $request->role_id;
        $users->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $user = new User();
        $this->saveRequest($user, $request);
        return redirect()->route('users.index')->with('msg', 'Added Successfully!');
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

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6|different:old_password',
            'new_password_confirmation' => 'required'
        ]);

        if (Hash::check($request->old_password, Auth::user()->password) == false)
        {
            return redirect()->back()->withErrors("Your current password does not matches with the password you provided. Please try again.");
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->withSuccess("Password changed successfully!");
    }
}
