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
        $this->data['schedules'] = Schedule::all();
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
            'bus_route_id' => 'required',
            'schedule_date' => 'required',
            'time_departure' => 'required',
            'time_arrival' => 'required',
        ], [
            'bus_id.required' => 'Bus is required',
            'user_id.required' => 'Bus driver is required',
            'bus_route_id.required' => 'Bus route is required',
            'schedule_date.required' => 'Schedule date is required',
            'time_departure.required' => 'Departure time is required',
            'time_arrival.required' => 'Arrival time is required',
        ]);
    }

    public function saveRequest($schedule, $request)
    {
        $schedule->bus_id = $request->bus_id;
        $schedule->user_id = $request->user_id;
        $schedule->bus_route_id = $request->bus_route_id;
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
