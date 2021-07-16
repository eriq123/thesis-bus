<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $this->data['buses'] = Bus::all();
        return view('pages.bus.index', $this->data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'bus_route' => 'required|max:255',
            'bus_platenumber' => 'required|max:255',
            'total_seats' => 'required|numeric',
        ], [
            'bus_route.required' => 'Bus Route is required.',
            'bus_platenumber.required' => 'Plate Number is required.',
            'total_seats.required' => 'Total Seats is required.',
            'total_seats.numeric' => 'Total Seats must be a number.'
        ]);

        $bus = new Bus();
        $bus->bus_route = $request->bus_route;
        $bus->bus_platenumber = $request->bus_platenumber;
        $bus->total_seats = $request->total_seats;
        $bus->is_fullybooked = $request->is_fullybooked;
        $bus->save();

        return redirect('/buses')->with('msg', 'Information Added!');
    }

    public function edit(Request $request, Bus $bus)
    {
        return view('pages.bus.edit', compact('bus'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'bus_route' => 'required|max:255',
            'bus_platenumber' => 'required|max:255',
            'total_seats' => 'required|numeric',
        ], [
            'bus_route.required' => 'Bus Route is required.',
            'bus_platenumber.required' => 'Plate Number is required.',
            'total_seats.required' => 'Total Seats is required.',
            'total_seats.numeric' => 'Total Seats must be a number.'
        ]);

        $bus = Bus::findorFail($id);
        $bus->bus_route = $request->bus_route;
        $bus->bus_platenumber = $request->bus_platenumber;
        $bus->total_seats = $request->total_seats;
        $bus->is_fullybooked = $request->is_fullybooked;
        $bus->save();

        return redirect('/buses')->with('msg', 'Information Updated!');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect('/buses')->with('delete', 'Information Deleted!');
    }
}
