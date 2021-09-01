<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $this->data['schedules'] = Schedule::with('user')->with('bus')->with('starting_point')->with('destination')->get();
        $this->data['buses'] = Bus::all();
        $this->data['drivers'] = User::where('role_id', 2)->get();
        $this->data['bus_routes'] = BusRoute::all();

        return view('admin.bus.schedules', $this->data);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'bus_id' => 'required',
            'user_id' => 'required',
            'starting_point_id' => 'required',
            'destination_id' => 'required',
            'fare' => 'required',
            'schedule_date' => 'required',
            'time_departure' => 'required',
            'time_arrival' => 'required',
        ], [
            'bus_id.required' => 'Bus is required',
            'user_id.required' => 'Bus driver is required',
            'starting_point_id.required' => 'Starting Point is required',
            'destination_id.required' => 'Destination is required',
            'fare.required' => 'Fare is required',
            'schedule_date.required' => 'Schedule date is required',
            'time_departure.required' => 'Departure time is required',
            'time_arrival.required' => 'Arrival time is required',
        ]);
    }

    public function saveRequest($schedule, $request)
    {
        $schedule->bus_id = $request->bus_id;
        $schedule->user_id = $request->user_id;
        $schedule->starting_point_id = $request->starting_point_id;
        $schedule->destination_id = $request->destination_id;
        $schedule->fare = $request->fare;
        $schedule->schedule_date = $request->schedule_date;
        $schedule->time_departure = $request->time_departure;
        $schedule->time_arrival = $request->time_arrival;
        $schedule->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $schedule = new Schedule();
        $this->saveRequest($schedule, $request);
        return redirect()->route('buses.schedules.index')->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $schedule = Schedule::find($request->id);
        $this->saveRequest($schedule, $request);

        return redirect()->route('buses.schedules.index')->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        Schedule::destroy($id);
        return redirect()->route('buses.schedules.index')->withSuccess('Deleted Successfully!');
    }
}
