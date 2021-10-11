<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        switch(Auth::user()->role_id) {
        case 1:
            return view('admin.index');
            break;
        case 2:
            return view('driver.index');
            break;
        case 3:
            return view('conductor.index');
            break;
        case 4:
            return view('passenger.index');
            break;
        default:
            return abort(401);
        }
    }
}
