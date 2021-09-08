<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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

    public function scheduleByBookingDetails(Request $request)
    {
        return response()->json($this->busBookingRepository->scheduleByBookingDetails($request));
    }

}
