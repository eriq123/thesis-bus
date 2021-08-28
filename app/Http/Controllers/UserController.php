<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $DEFAULT_REDIRECT_ROUTE = 'users.index';
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->data['users'] = User::with('role')->get();
        $this->data['roles'] = Role::orderByDesc('name')->get();
        return view('admin.user.index', $this->data);
    }

    public function store(Request $request)
    {
        $this->userRepository->store($request);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->with('msg', 'Added Successfully!');
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $this->userRepository->update($request, $user);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Updated Successfully!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $this->userRepository->destroy($id);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Deleted Successfully!');
    }

    public function changePassword(Request $request)
    {
        $this->userRepository->validatePassword($request);

        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            return redirect()->back()->withErrors("Your current password does not matches with the password you provided. Please try again.");
        }

        $this->userRepository->changePassword($request);

        return redirect()->back()->withSuccess("Password changed successfully!");
    }
}
