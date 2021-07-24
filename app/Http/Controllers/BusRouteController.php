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
            'name' => 'required|max:255',
        ], [
            'name.required' => 'Bus route name is required.',
            'name.max' => 'Maximum of 255 characters only.'
        ]);
    }

    public function saveRequest($bus, $request)
    {
        $bus->name = $request->name;
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
