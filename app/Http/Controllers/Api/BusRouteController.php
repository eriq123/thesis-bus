<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return response()->json($this->busRouteRepository->index(), 200);
    }
}
