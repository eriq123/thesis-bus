<?php

namespace App\Http\Controllers;

use App\Models\BusRoute;
use Illuminate\Http\Request;

class BusRouteController extends Controller
{
    public function index()
    {
        $this->data['bus_routes'] = BusRoute::with('bus_route_start')->with('bus_route_destination')->get();
        return view('admin.bus.routes', $this->data);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'bus_route_start_id' => 'required|integer',
            'bus_route_destination_id' => 'required|integer',
            'fare' => 'required|integer',
        ], [
            'bus_route_start_id.required' => 'Starting Point is required.',
            'bus_route_destination_id.required' => 'Destination is required.',
            'fare.required' => 'Fare is required.',
            'fare.integer' => 'Fare must be an integer.',
        ]);
    }

    public function saveRequest($bus_route, $request)
    {
        $bus_route->bus_route_start_id = $request->bus_route_start_id;
        $bus_route->bus_route_destination_id = $request->bus_route_destination_id;
        $bus_route->fare = $request->fare;
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
