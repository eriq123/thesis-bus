<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class BusBookingRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['schedules'] = Schedule::all();
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['bookings'] = Booking::all();
        $this->data['passengers'] = User::where('role_id', 4)->get();

        return $this->data;
    }

    private function saveRequest($bus, $request)
    {
        $bus->plate_number = $request->plate_number;
        $bus->type = $request->type;
        $bus->capacity = $request->capacity;
        $bus->save();
    }

    private function validateRequest($request, $isUpdate = false)
    {
        $rules = [
            'plate_number' => 'required|max:255',
            'type' => 'required|max:255',
            'capacity' => 'required|numeric',
        ];

        $errorMessages = [
            'plate_number.required' => 'Plate number field is required.',
            'type.required' => 'Bus route field is required.',
            'capacity.required' => 'Total seats field is required.',
            'capacity.numeric' => 'Total seats field must be a number.'
        ];

        if ($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $this->validateRequest($request);
        $bus = new Bus();
        $this->saveRequest($bus, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request)
    {
        $this->validateRequest($request, true);
        $bus = Bus::find($request->id);
        $this->saveRequest($bus, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Bus::destroy($id);
    }
}
