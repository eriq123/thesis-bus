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
            ->with('bus')
            ->with([
                'schedule' => function($q) {
                    $q->with('starting_point')->with('destination');
                }
            ])
            ->when(Auth::user()->role_id == 2, function($q) {
                return $q->where('driver_id', Auth::id());
            })
            ->when(Auth::user()->role_id == 3, function($q) {
                return $q->where('conductor_id', Auth::id());
            })
            ->when(Auth::user()->role_id == 4, function($q) {
                return $q->where('user_id', Auth::id());
            })
            ->get();
        return $this->data;
    }

    public function edit()
    {
        $this->data['bus_routes'] = BusRoute::orderBy('name')->get();
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

    private function openBookingTotalQuantity($scheduleId){
        return Booking::where('schedule_id', $scheduleId)->where('status', 'Open')->sum('quantity');
    }

    private function checkAvailableSeats($request, $schedule)
    {
        $capacity = $schedule->bus->capacity;
        $seats_taken = $this->openBookingTotalQuantity($request->schedule_id);

        $seats_ramaining = $capacity - $seats_taken;
        $seats_taken += $request->quantity;
        return $seats_taken > $seats_ramaining;
    }

    public function processBooking($request, $isApi = false)
    {
        $isUpdate = $request->id == 0 ? false : true;
        $this->validateBooking($request, $isUpdate);
        $schedule = Schedule::with('bus')->findOrFail($request->schedule_id);
        if($this->checkAvailableSeats($request, $schedule)) {
            $errorMessage = 'The remaining seats are insufficient to fulfill the transaction.';
            if($isApi) return response()->json($errorMessage, 403);
            return redirect()->back()->withErrors($errorMessage);
        }

        if($request->id == 0) {
            $booking = new Booking();
            $successMessage = 'Added Successfully!';
        } else {
            $booking = Booking::find($request->id);
            $successMessage = 'Updated Successfully!';
        }

        $booking->user_id = $request->user_id;
        $booking->bus_id = $schedule->bus_id;
        $booking->driver_id = $schedule->driver_id;
        $booking->conductor_id = $schedule->conductor_id;
        $booking->schedule_id = $schedule->id;
        $booking->fare_amount = $schedule->fare;
        $booking->quantity = $request->quantity;
        $booking->grand_total = $request->quantity * $schedule->fare;
        $booking->status = 'Open';
        $booking->save();

        if($isApi) return $booking;

        return redirect()->route('buses.bookings.index')->withSuccess($successMessage);
    }

    public function scheduleByBookingDetails($request)
    {
        $schedule = Schedule::when($request->starting_point_id, function($q) use ($request){
            return $q->where('starting_point_id', $request->starting_point_id);
        })
        ->when($request->destination_id, function($q) use ($request){
            return $q->where('destination_id', $request->destination_id);
        })
        ->when($request->schedule_date, function($q) use ($request){
            return $q->where('schedule_date', $request->schedule_date);
        })
        ->with('bus')
        ->get();

        return $schedule->map(function($schedule) {
            $seats_taken = $this->openBookingTotalQuantity($schedule->id);
            $schedule->available_seats = $schedule->bus->capacity - $seats_taken;
            return $schedule;
        });
    }
}
