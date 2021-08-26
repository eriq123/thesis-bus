<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusRoute;
use Illuminate\Support\Facades\Validator;

class BusRouteRepository
{
    public function index()
    {
        $this->data['bus_routes'] = BusRoute::all();
        return $this->data;
    }

    private function saveRequest($bus_route, $request)
    {
        $bus_route->name = $request->name;
        $bus_route->save();
    }

    public function validateRequest($request, $isUpdate = false)
    {
        $rules = [
            'name' => 'required',
        ];

        $errorMessages = [
            'name.required' => 'Name is required.',
        ];

        if($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    public function store($request)
    {
        $bus_route = new BusRoute();
        $this->validateRequest($request);
        $this->saveRequest($bus_route, $request);
    }

    public function update($request)
    {
        $bus_route = BusRoute::find($request->id);
        $this->validateRequest($request, true);
        $this->saveRequest($bus_route, $request);
    }

    public function destroy($id)
    {
        BusRoute::destroy($id);
    }
}
