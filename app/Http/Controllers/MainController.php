<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            break;
        case 3:
            break;
        case 4:
            return view('passenger.index');
            break;
        default:
            return abort(401);
        }
    }
}
