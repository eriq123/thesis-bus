<?php

namespace App\Http\Controllers;

use App\Models\BusRoute;
use Illuminate\Http\Request;

class BusRouteController extends Controller
{
    public function index()
    {
        $this->data['bus_routes'] = BusRoute::all();
        return view('admin.bus.routes', $this->data);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'Name is required.',
        ]);
    }

    public function saveRequest($bus_route, $request)
    {
        $bus_route->name = $request->name;
        $bus_route->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $bus_route = new BusRoute();
        $this->saveRequest($bus_route, $request);
        return redirect()->route('buses.routes.index')->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $bus_route = BusRoute::find($request->id);
        $this->saveRequest($bus_route, $request);

        return redirect()->route('buses.routes.index')->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        BusRoute::destroy($id);
        return redirect()->route('buses.routes.index')->withSuccess('Deleted Successfully!');
    }

}
