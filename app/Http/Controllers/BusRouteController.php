<?php

namespace App\Http\Controllers;

use App\Repositories\BusRouteRepository;
use Illuminate\Http\Request;

class BusRouteController extends Controller
{
    private $DEFAULT_REDIRECT_ROUTE = 'buses.routes.index';
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
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->busRouteRepository->update($request);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Updated Successfully!');
    }

    public function destroy($id)
    {
        $this->busRouteRepository->destroy($id);
        return redirect()->route($this->DEFAULT_REDIRECT_ROUTE)->withSuccess('Deleted Successfully!');
    }
}
