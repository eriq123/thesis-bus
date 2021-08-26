<?php

namespace App\Http\Controllers;

use App\Models\BusRoute;
use App\Repositories\BusRouteRepository;
use Illuminate\Http\Request;

class BusRouteController extends Controller
{
    private $busRouteRepository;

    public function __construct(BusRouteRepository $busRouteRepository)
    {
        $this->busRouteRepository = $busRouteRepository;
    }

    public function index()
    {
        return view('admin.bus.routes', $this->busRouteRepository->index());
    }

    public function store(Request $request)
    {
        $this->busRouteRepository->store($request);
        return redirect()->route('buses.routes.index')->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->busRouteRepository->update($request);
        return redirect()->route('buses.routes.index')->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        $this->busRouteRepository->destroy($id);
        return redirect()->route('buses.routes.index')->withSuccess('Deleted Successfully!');
    }

}
