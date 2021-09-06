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
        return view('admin.bus.booking.index', $this->busBookingRepository->index());
    }

    public function add()
    {
        $this->data = $this->busBookingRepository->edit();
        $this->data['booking'] = [];
        return view('admin.bus.booking.add-update', $this->data);
    }

    public function edit($id)
    {
        $this->data = $this->busBookingRepository->edit();
        $this->data['booking'] = Booking::find($id);
        return view('admin.bus.booking.add-update', $this->data);
    }

    public function updateStatus(Request $request)
    {
        $booking = Booking::find($request->id);
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->withSuccess('Status has been updated');
    }

    public function destroy($id)
    {
        $this->busBookingRepository->destroy($id);
        return redirect()->route('buses.bookings.index')->withSuccess('Deleted Successfully!');
    }

    public function submitProcess(Request $request)
    {
        return $this->busBookingRepository->processBooking($request);
    }

    public function findScheduleByRouteIDs(Request $request)
    {
        $schedule = Schedule::when($request->starting_point_id, function($q) use ($request){
            return $q->where('starting_point_id', $request->starting_point_id);
        })
        ->when($request->destination_id, function($q) use ($request){
            return $q->where('destination_id', $request->destination_id);
        })
        ->when($request->schedule_date, function($q) use ($request){
            return $q->where('schedule_date', $request->schedule_date);
        })
        ->with('bus')
        ->get();

        $schedule->map(function($schedule) {
            $seats_taken = Booking::where('bus_id', $schedule->bus->id)->where('status', 'Open')->count();
            $schedule->available_seats = $schedule->bus->capacity - $seats_taken;
            return $schedule;
        });

        return response()->json($schedule);
    }

}
