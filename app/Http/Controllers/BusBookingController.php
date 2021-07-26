<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class BusBookingController extends Controller
{
    public function index()
    {
        $this->data['schedules'] = Schedule::all();
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['bookings'] = Booking::all();
        $this->data['passengers'] = User::where('role_id', 4)->get();

        return view('admin.bus.bookings', $this->data);
    }

    public function scheduleByRouteID(Request $request)
    {
        $schedule = Schedule::where('bus_route_id', $request->id)->get();

        return response()->json($schedule);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'schedule_id' => 'required',
            'fare_amount' => 'required|integer',
            'quantity' => 'required|integer',
            'grand_total' => 'required|integer',
        ], [
            'user_id.required' => 'Passenger ID is required',
            'schedule_id.required' => 'Schedule ID is required',
            'fare_amount.required' => 'Fare amount is required',
            'fare_amount.integer' => 'Fare amount must be an integer',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be an integer',
            'grand_total.required' => 'Grand total is required',
            'grand_total.integer' => 'Grand total must be an integer',
        ]);
    }

    public function saveRequest($schedule, $request)
    {
        $schedule->user_id = $request->user_id;
        $schedule->schedule_id = $request->schedule_id;
        $schedule->fare_amount = $request->fare_amount;
        $schedule->quantity = $request->quantity;
        $schedule->grand_total = $request->grand_total;
        $schedule->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $schedule = new Booking();
        $this->saveRequest($schedule, $request);
        return redirect()->route('buses.bookings.index')->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $schedule = Booking::find($request->id);
        $this->saveRequest($schedule, $request);

        return redirect()->route('buses.bookings.index')->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        Booking::destroy($id);
        return redirect()->route('buses.bookings.index')->withSuccess('Deleted Successfully!');
    }

}
