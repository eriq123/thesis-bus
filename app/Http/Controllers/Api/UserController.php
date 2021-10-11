<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return response()->json([
            'user' => Auth::user(),
        ], 200);
    }

    public function update(Request $request)
    {
        $this->userRepository->update($request, Auth::user());

        return response()->json(Auth::user(), 200);
    }

    public function changePassword(Request $request)
    {
        $this->userRepository->validatePassword($request);

        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            return response()->json([
                'error' => '"Your current password does not matches with the password you provided. Please try again."',
            ], 401);
        }

        $this->userRepository->changePassword($request);

        return response()->json($this->userRepository->changePassword($request), 200);
    }
}
