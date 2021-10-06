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
    private $defaultBusBookingId = -1;
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
        if($request->user_status == 'unregistered') $rules['name'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    private function getAvailableSeats($capacity, $seats_taken)
    {
        return $capacity - $seats_taken;
    }

    private function openBookingTotalQuantity($scheduleId, $additionalQuantity = 0){
        $seats_taken = Booking::where('schedule_id', $scheduleId)->whereIn('status_id', [1,2])->sum('quantity');
        return $seats_taken += $additionalQuantity;
    }

    private function checkIfNoSeatsAvailable($request, $schedule)
    {
        $seats_taken = $this->openBookingTotalQuantity($request->schedule_id, $request->quantity);
        return $schedule->bus->capacity < $seats_taken;
    }

    public function index()
    {
        $this->data['bookings'] = Booking::with('user')
            ->with('bus')
            ->with('status')
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
            ->get()
            ->map(function($booking) {
                $booking->schedule->seats_available = $this->getAvailableSeats(
                    $booking->schedule->bus->capacity,
                    $this->openBookingTotalQuantity($booking->schedule->id)
                );
                return $booking;
            });

        return $this->data;
    }

    public function edit()
    {
        $this->data['bus_routes'] = BusRoute::orderBy('name')->get();
        $this->data['passengers'] = User::where('role_id', 4)->orderBy('name')->get();
        $this->data['schedules'] = [];

        return $this->data;
    }

    public function updateStatus($request)
    {
        $booking = Booking::find($request->id);
        $booking->status_id = $request->status_id;
        $booking->save();

        return $booking;
    }

    public function destroy($id)
    {
        Booking::destroy($id);
    }

    public function processBooking($request, $isApi = false)
    { 
        try{

            $userName = User::find($request->user_id)->name;
            $isUpdate = $request->id == $this->defaultBusBookingId ? false : true;

            $this->validateBooking($request, $isUpdate);

            $schedule = Schedule::with('bus')->findOrFail($request->schedule_id);
           
            if($this->checkIfNoSeatsAvailable($request, $schedule)) {
                $errorMessage = 'The remaining seats are insufficient to fulfill the transaction.';
                if($isApi) return response()->json($errorMessage, 403);
                return redirect()->back()->withErrors($errorMessage);
            }


            if($request->id == $this->defaultBusBookingId) {
                $booking = new Booking();
                $successMessage = 'Added Successfully!';

            } else {
                  $booking = Booking::find($request->id);
                $successMessage = 'Updated Successfully!';
            }
                                
            if($request->user_status == 'existing') {

                $booking->user_name = User::find($request->user_id)->name;
                $booking->user_id = $request->user_id;
            } else {
                $booking->user_name = $userName;
                $booking->user_id = $request->user_id;
            }
            $booking->bus_id = $schedule->bus_id;
            $booking->driver_id = $schedule->driver_id;
            $booking->conductor_id = $schedule->conductor_id;
            $booking->schedule_id = $schedule->id;
            $booking->fare_amount = $schedule->fare;
            $booking->quantity = $request->quantity;
            $booking->grand_total = $request->quantity * $schedule->fare;
            $booking->status_id = Auth::user()->role_id == 1 ? 2 : 1;
            $booking->save();

            if($isApi) return $booking;
            return redirect()->route('buses.bookings.index')->withSuccess($successMessage);

        }catch(Exception $e){
            return redirect()->route('buses.bookings.index')->withSuccess($successMessage);
        }

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

        return $schedule->map(function($item) {
            $item->seats_available = $this->getAvailableSeats($item->bus->capacity, $this->openBookingTotalQuantity($item->id));
            return $item;
        });
    }
}
