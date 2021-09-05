<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusBookingRepository
{
    public function index()
    {
        $this->data['bookings'] = Booking::with('user')
            ->when(Auth::user()->role_id == 4, function($q) {
                return $q->where('user_id', Auth::id());
            })
            ->get();
        return $this->data;
    }

    public function edit()
    {
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['passengers'] = User::where('role_id', 4)->orderBy('name')->get();
        $this->data['schedules'] = [];

        return $this->data;
    }

    public function destroy($id)
    {
        Booking::destroy($id);
    }

    private function validateBooking($request, $isUpdate = false){
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

        if ($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    public function processBooking($request)
    {
        $this->validateBooking($request, true);
        $schedule = Schedule::findOrFail($request->schedule_id);

        if($request->id == 0) {
            $booking = new Booking();
            $successMessage = 'Added Successfully!';
        } else {
            $booking = Booking::find($request->id);
            $successMessage = 'Updated Successfully!';
        }

        $booking->user_id = $request->user_id;
        $booking->bus_id = $schedule->bus_id;
        $booking->schedule_id = $schedule->id;
        $booking->fare_amount = $schedule->fare;
        $booking->quantity = $request->quantity;
        $booking->grand_total = $request->quantity * $schedule->fare;
        $booking->status = 'Open';
        $booking->save();

        return redirect()->route('buses.bookings.index')->withSuccess($successMessage);
    }
}
