<?php

namespace App\Repositories;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusRepository
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['buses'] = Bus::all();
        return $this->data;
    }

    private function saveRequest($bus, $request)
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
    public function store($request)
    {
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
