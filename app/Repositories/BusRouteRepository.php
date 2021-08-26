<?php

namespace App\Repositories;

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

        if ($isUpdate) $rules['id'] = 'required';

        Validator::make($request->all(), $rules, $errorMessages)->validate();
    }

    public function store($request)
    {
        $this->validateRequest($request);
        $bus_route = new BusRoute();
        $this->saveRequest($bus_route, $request);
    }

    public function update($request)
    {
        $this->validateRequest($request, true);
        $bus_route = BusRoute::find($request->id);
        $this->saveRequest($bus_route, $request);
    }

    public function destroy($id)
    {
        BusRoute::destroy($id);
    }
}
