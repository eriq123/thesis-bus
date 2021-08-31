<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\BusBookingRepository;
use Illuminate\Http\Request;

class BusBookingController extends Controller
{
    private $busBookingRepository;

    public function __construct(BusBookingRepository $busBookingRepository)
    {
        $this->busBookingRepository = $busBookingRepository;
    }

    public function index()
    {
        return view('admin.bus.bookings', $this->busBookingRepository->index());
    }

    public function destroy($id)
    {
        $this->busBookingRepository->destroy($id);
        return redirect()->route('buses.bookings.index')->withSuccess('Deleted Successfully!');
    }

    public function process()
    {
        $this->data['bus_routes'] = BusRoute::all();
        $this->data['passengers'] = User::where('role_id', 4)->orderBy('name')->get();
        $this->data['schedules'] = [];

        return view('admin.bus.booking.add-update', $this->data);
    }

    public function submitProcess(Request $request)
    {
        $this->busBookingRepository->processBooking($request);
        return redirect()->route('buses.bookings.index')->withSuccess('Added Successfully!');
    }

    public function findScheduleByRouteIDs(Request $request)
    {
        $schedule = Schedule::when($request->starting_point_id, function($q) use ($request){
            return $q->where('starting_point_id', $request->starting_point_id);
        })
        ->when($request->destination_id, function($q) use ($request){
            return $q->where('destination_id', $request->destination_id);
        })
        ->with('bus')
        ->get();

        return response()->json($schedule);
    }

}
