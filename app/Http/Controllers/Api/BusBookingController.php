<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusBookingController extends Controller
{
    public function stepOne()
    {
        $bus_routes = BusRoute::all();
        return response()->json($bus_routes, 200);
    }

    public function stepTwo(Request $request)
    {
        $this->validate($request, [
            'starting_point_id' => 'required',
            'destination_id' => 'required',
            'schedule_date' => 'required',
        ]);

        $schedule = Schedule::where('starting_point_id', $request->starting_point_id)
            ->where('destination_id', $request->destination_id)
            ->where('schedule_date', $request->schedule_date)
            ->with('bus')
            ->get();

        return response()->json($schedule, 200);
    }

    public function confirm(Request $request)
    {
        $this->validate($request, [
            'schedule_id' => 'required',
            'quantity' => 'required',
        ]);

        $schedule = Schedule::find($request->schedule_id);

        $booking = new Booking();
        $booking->user_id = Auth::user()->id;
        $booking->schedule_id = $request->schedule_id;
        $booking->fare_amount = $schedule->fare;
        $booking->quantity = $request->quantity;
        $booking->grand_total = $request->quantity * $schedule->fare;
        $booking->status = 'Open';
        $booking->save();

    }
}
