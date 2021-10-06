<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BusBookingRepository;

class paymentController extends Controller
{

    private $busBookingRepository;

    public function __construct(BusBookingRepository $busBookingRepository)
    {
        $this->busBookingRepository = $busBookingRepository;
    }

    public function source($id)
    {
        $booking = Booking::find($id);
        $gcashSource = Paymongo::source()->create([
            'type' => 'gcash',
            'amount' => $booking->grand_total,
            'currency' => 'PHP',
            'redirect' => [
                'success' => 'http://localhost:8000/payments/success/'.$id,
                'failed' => 'http://localhost:8000/payments/fail'
            ]
        ]);
        $booking->source_id = $gcashSource->id;
        $booking->save();
        return redirect($gcashSource->redirect['checkout_url']);
    }

    public function success($id)
    {
        $booking = Booking::find($id);
        $payment = Paymongo::payment()->create([
            'amount' => $booking->grand_total,
            'currency' => 'PHP',
            'description' => 'ID: '.$id.', Name: '.$booking->user_name.', Route: '.$booking->schedule->starting_point->name . " to " .$booking->schedule->destination->name,
            'statement_descriptor' => 'Test Paymongo',
            'source' => [
                'id' => $booking->source_id,
                'type' => 'source'
            ]
        ]);
        $booking->status_id = 2;
        $booking->save();
        // update status of transaction
        // return sucess payment page
        return redirect()->route('buses.bookings.index')->withSuccess('Paid Successfully!');
    }

    public function fail() {

    }
}
