<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use Illuminate\Http\Request;

class BusRouteController extends Controller
{
    public function all()
    {
        $this->data['bus_routes'] = BusRoute::all();
        return response()->json($this->data, 200);
    }
}
