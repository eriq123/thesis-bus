<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['buses'] = Bus::all();
        return view('admin.bus.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'plate_number' => 'required|max:255',
            'type' => 'required|max:255',
            'capacity' => 'required|numeric',
        ], [
            'plate_number.required' => 'Plate Number is required.',
            'type.required' => 'Bus Route is required.',
            'capacity.required' => 'Total Seats is required.',
            'capacity.numeric' => 'Total Seats must be a number.'
        ]);
    }

    public function saveRequest($bus, $request)
    {
        $bus->plate_number = $request->plate_number;
        $bus->type = $request->type;
        $bus->capacity = $request->capacity;
        $bus->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $bus = new Bus();
        $this->saveRequest($bus, $request);
        return redirect()->route('buses.index')->withSuccess('Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $bus = Bus::find($request->id);
        $this->saveRequest($bus, $request);

        return redirect()->route('buses.index')->withSuccess('Updated Successfully!');
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
        return redirect()->route('buses.index')->withSuccess('Deleted Successfully!');
    }
}
