<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusRoute;

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

    public function store($request)
    {
        $bus_route = new BusRoute();
        $this->saveRequest($bus_route, $request);
    }

    public function update($request)
    {
        $bus_route = BusRoute::find($request->id);
        $this->saveRequest($bus_route, $request);
    }

    public function destroy($id)
    {
        BusRoute::destroy($id);
    }
}
