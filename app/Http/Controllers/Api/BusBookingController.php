<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\Bus;
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

    public function uploadPayment(Request $request){

        $itemId      = $request->itemId;
        $referenceId = $request->referenceId;
        $booking     = Booking::find($itemId);

        $booking->payment_source_id = $referenceId;
        $booking->payment_image     = "storage/app/public/".$referenceId.".png";
        $booking->status_id         = 2;

        $booking->save();
        
        $result["status"] = TRUE;
        $result["remarks"] = "Ticket Paid Successfully";


         return response()->json($result, 200);
    }
    public function getConductorBookedBusDetails(Request $request){


       
        $userId      = $request->userId;
        $health      = $request->health;

        if($health == "get"){
            
            $schedule    = Schedule::select('id','bus_id','conductor_id','status')->where('conductor_id',"=", $userId)->where('status',"=", 'open')->get();
            $schedule    = $schedule[0]->getAttributes();

       
        $result["busId"] =$schedule['bus_id'];
        $result["status"] = TRUE;
        $result["remarks"] = "Current Location";

        }else if($health == "update"){


            $busId      = $request->busId;
            $longitude  = $request->longitude;
            $latitude   = $request->latitude;

            $bus        = Bus::find($busId);

          
            // $bus        = $bus->getAttributes();
            // $bus['long']  = $longitude;
            // $bus['lat'] = $latitude;
            $bus->long  = $longitude;
            $bus->lat = $latitude;

            
            $bus->save();

            $result["status"] = TRUE;
            $result["remarks"] = "Location Updated Successfully";
        }elseif($health == 'approve'){

            $bookingId = $request->bookingId;
            $booking   = Booking::find($bookingId);

            $booking->status_id = 6;

            $booking->save();

            $result["status"] = TRUE;
            $result["remarks"] = "Verfied onBoard Successfully";


        }

      


         return response()->json($result, 200);
    }
}
