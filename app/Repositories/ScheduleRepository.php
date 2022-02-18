<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ScheduleRepository
{
    public function index()
    {
        $this->data['schedules'] = Schedule::with('driver')->with('conductor')->with('bus')->with('starting_point')->with('destination')->get();
        $this->data['buses'] = Bus::orderBy('plate_number')->get();
        $this->data['drivers'] = User::orderBy('name')->where('role_id', 2)->get();
        $this->data['conductors'] = User::orderBy('name')->where('role_id', 3)->get();
        $this->data['bus_routes'] = BusRoute::orderBy('name')->get();
        return $this->data;
    }

    private function saveRequest($schedule, $request)
    {
        $schedule->bus_id = $request->bus_id;
        $schedule->driver_id = $request->driver_id;
        $schedule->conductor_id = $request->conductor_id;
        $schedule->starting_point_id = $request->starting_point_id;
        $schedule->destination_id = $request->destination_id;
        $schedule->fare = $request->fare;
        $schedule->schedule_date = $request->schedule_date;
        $schedule->time_departure = $request->time_departure;
        $schedule->time_arrival = $request->time_arrival;
        $schedule->status =$request->status;
        $schedule->save();

        $bus = Bus::find($request->bus_id);
        $bus->status = 'booked';
        $bus->save();

        $driver = User::find($request->driver_id);
        $driver->status = 'booked';
        $driver->save();

        $conductor = User::find($request->conductor_id);
        $conductor->status = 'booked';
        $conductor->save();
    }

    private function validateRequest($request, $isUpdate = false)
    {
        $rules = [
            'bus_id' => 'required',
            'driver_id' => 'required',
            'conductor_id' => 'required',
            'starting_point_id' => 'required',
            'destination_id' => 'required',
            'fare' => 'required | numeric',
            'schedule_date' => 'required',
            'time_departure' => 'required',
            'time_arrival' => 'required',
        ];

        $errorMessages = [
            'bus_id.required' => 'Bus is required',
            'driver_id.required' => 'Bus driver is required',
            'conductor_id.required' => 'Bus conductor is required',
            'starting_point_id.required' => 'Starting Point is required',
            'destination_id.required' => 'Destination is required',
            'fare.required' => 'Fare is required',
            'fare.numeric' => 'Fare must be number',
            'schedule_date.required' => 'Schedule date is required',
            'time_departure.required' => 'Departure time is required',
            'time_arrival.required' => 'Arrival time is required',
        ];

        if ($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    public function store($request)
    {
        $this->validateRequest($request);
        $schedule = new Schedule();
        $this->saveRequest($schedule, $request);
    }

    public function update($request)
    {
        $this->validateRequest($request, true);
        $schedule = Schedule::find($request->id);
        $this->saveRequest($schedule, $request);
    }

    public function destroy($id)
    {
        Schedule::destroy($id);
    }
}
