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
        $this->data['schedules'] = Schedule::all();
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['bookings'] = Booking::all();
        $this->data['passengers'] = User::where('role_id', 4)->get();

        return $this->data;
    }
}
