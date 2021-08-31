<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class BusBookingRepository
{
    public function index()
    {
        return Booking::with('user')->with('schedule')->get();
    }

    public function destroy($id)
    {
        Booking::destroy($id);
    }

    private function validateBooking($request){
        $rules = [
            'user_id' => 'required',
            'schedule_id' => 'required',
            'quantity' => 'required|integer',
        ];

        $errorMessages = [
            'schedule_id.required' => 'Please select a schedule.',
            'user_id.required' => 'Please select a user.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity should be an integer.',
        ];

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    public function processBooking($request)
    {
        $this->validateBooking($request);
        $schedule = Schedule::findOrFail($request->schedule_id);

        $booking = new Booking();
        $booking->user_id = $request->user_id;
        $booking->schedule_id = $schedule->id;
        $booking->fare_amount = $schedule->fare;
        $booking->quantity = $request->quantity;
        $booking->grand_total = $request->quantity * $schedule->fare;
        $booking->status = 'Open';
        $booking->save();

        return $booking;
    }
}
