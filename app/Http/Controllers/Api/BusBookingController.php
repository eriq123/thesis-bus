<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Repositories\BusBookingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusBookingController extends Controller
{
    private $busBookingRepository;

    public function __construct(BusBookingRepository $busBookingRepository)
    {
        $this->busBookingRepository = $busBookingRepository;
    }

    public function index()
    {
        return response()->json($this->busBookingRepository->index(), 200);
    }

    public function stepOne()
    {
        $this->data['routes'] = BusRoute::all();
        return response()->json($this->data, 200);
    }

    public function stepTwo(Request $request)
    {
        $this->validate($request, [
            'starting_point_id' => 'required',
            'destination_id' => 'required',
            'schedule_date' => 'required',
        ]);

        $this->data['schedule'] = Schedule::where('starting_point_id', $request->starting_point_id)
            ->where('destination_id', $request->destination_id)
            ->where('schedule_date', $request->schedule_date)
            ->with('bus')
            ->get();

        return response()->json($this->data, 200);
    }

    public function confirm(Request $request)
    {
        $request->merge([
            "user_id" => Auth::user()->id
        ]);
        return response()->json($this->busBookingRepository->processBooking($request, true), 200);
    }

    public function upload(Request $request){

         $result = $request->all();

         echo "<pre>";print_r($result);echo "</pre>";
         die("I am here.. LA ALA LALALA A");
    }
}
