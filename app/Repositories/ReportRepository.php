<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportRepository
{

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
            })->where('status_id','=',2)->orWhere('status_id','=',3)
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


    private function getAvailableSeats($capacity, $seats_taken)
    {
        return $capacity - $seats_taken;
    }

    private function openBookingTotalQuantity($scheduleId, $additionalQuantity = 0){
        $seats_taken = Booking::where('schedule_id', $scheduleId)->whereIn('status_id', [1,2])->sum('quantity');
        return $seats_taken += $additionalQuantity;
    }




  



 
}
