<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class BusBookingController extends Controller
{
    public function showStepOne()
    {
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['passengers'] = User::where('role_id', 4)->orderBy('name')->get();

        return view('admin.bus.booking.show-step-1', $this->data);
    }

    public function submitStepOne(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'starting_point_id' => 'required',
            'destination_id' => 'required',
            'schedule_date' => 'required',
        ]);

        session([
            'user_id' => $request->user_id,
            'starting_point_id' => $request->starting_point_id,
            'destination_id' => $request->destination_id,
            'schedule_date' => $request->schedule_date,
        ]);
        return redirect()->route('buses.bookings.show.step.two');
    }

    public function showStepTwo()
    {
        $starting_point_id = session('starting_point_id');
        $destination_id = session('destination_id');
        $schedule_date = session('schedule_date');
        $this->data['schedules'] = Schedule::where('starting_point_id', $starting_point_id)
            ->where('destination_id', $destination_id)
            ->where('schedule_date', $schedule_date)
            ->get();

        $this->data['from'] = BusRoute::find($starting_point_id);
        $this->data['to'] = BusRoute::find($destination_id);
        $this->data['date'] = date('M d, Y', strtotime($schedule_date));
        // $this->data['schedules'] = Schedule::all();

        // $this->data['schedules'] = Schedule::where('starting_point_id', 1)
        //     ->where('destination_id', 2)
        //     ->where('schedule_date', 'Aug 29 2021')
        //     ->get();


        return view('admin.bus.booking.show-step-2', $this->data);
    }

    public function submitStepTwo(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer',
            'time_arrival' => 'required',
            'time_departure' => 'required',
        ],[
            'time_arrival.required'
        ]);

        $starting_point_id = session('starting_point_id');
        $destination_id = session('destination_id');
        $schedule_date = session('schedule_date');

        $schedule = Schedule::where('starting_point_id', $starting_point_id)
            ->where('destination_id', $destination_id)
            ->where('schedule_date', $schedule_date)
            ->where('time_arrival', $request->time_arrival)
            ->where('time_departure', $request->time_departure)
            ->first();

        $booking = new Booking();
        $booking->user_id = session('user_id');
        $booking->schedule_id = $schedule->id;
        $booking->fare_amount = $schedule->fare;
        $booking->quantity = $request->quantity;
        $booking->grand_total = $request->quantity * $schedule->fare;
        $booking->status = 'Open';
        $booking->save();

        return redirect()->route('buses.bookings.index')->withSuccess('Added Successfully!');
    }

    public function index()
    {
        $this->data['schedules'] = Schedule::all();
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['bookings'] = Booking::all();
        $this->data['passengers'] = User::where('role_id', 4)->get();

        return view('admin.bus.bookings', $this->data);
    }

    public function destroy($id)
    {
        Booking::destroy($id);
        return redirect()->route('buses.bookings.index')->withSuccess('Deleted Successfully!');
    }

}
