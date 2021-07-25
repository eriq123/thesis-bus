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
            'starting_point' => 'required|max:255',
            'destination' => 'required|max:255',
            'fare' => 'required|integer',
        ], [
            'starting_point.required' => 'Starting Point is required.',
            'starting_point.max' => 'Maximum of 255 characters only.',
            'destination.required' => 'Destination is required.',
            'destination.max' => 'Maximum of 255 characters only.',
            'fare.required' => 'Fare is required.',
            'fare.integer' => 'Fare must be an integer.',
        ]);
    }

    public function saveRequest($bus, $request)
    {
        $bus->starting_point = $request->starting_point;
        $bus->destination = $request->destination;
        $bus->fare = $request->fare;
        $bus->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $bus = new BusRoute();
        $this->saveRequest($bus, $request);
        return redirect()->route('buses.routes.index')->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $bus = BusRoute::find($request->id);
        $this->saveRequest($bus, $request);

        return redirect()->route('buses.routes.index')->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        BusRoute::destroy($id);
        return redirect()->route('buses.routes.index')->withSuccess('Deleted Successfully!');
    }

}
