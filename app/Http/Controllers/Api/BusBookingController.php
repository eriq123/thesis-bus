<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\User;
use App\Repositories\BusBookingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $booking->payment_image     = $referenceId.".png";
        $booking->status_id         = 2;
        $userId                     = $booking->user_id;  

        $booking->save();

        $user        = User::find($userId);
        $user->gcash_number = $request->gcash_number;
        $user->save();
        
        $result["status"] = TRUE;
        $result["remarks"] = "Wait for Admin approval ! Ticket Paid Successfully ";


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


        }elseif($health == 'end'){

            $general_id ="";
            $results = DB::select( DB::raw("SELECT * FROM bookings WHERE conductor_id = '$userId' AND (status_id = 6 OR status_id = 1 OR status_id = 2 OR status_id = 3)  "));
           
            for ($i = 0, $c = count($results); $i < $c; ++$i)
            {
                $results[$i] = (array) $results[$i];
            }

            for ($j = 0, $d = count($results); $j < $d; ++$j)
            {
                $schedule_id            = $results[$j]['schedule_id'];

                $schedules              = Schedule::find($schedule_id);

                if($schedules->status=="open"){

                    $driver_id              = $results[$j]['driver_id'];
                    $bus_id                 = $results[$j]['bus_id'];
                    $passenger_id           = $results[$j]['user_id'];
                    $booking_id             = $results[$j]['id'];
                    $schedule_id_open       = $results[$j]['schedule_id'];


                    $conductorResult   = User::find($userId);
                    $conductorResult->status = 'open';
                    $conductorResult->save();

                    $driverResult      = User::find($driver_id);
                    $driverResult->status = 'open';
                    $driverResult->save();

                    $busResult      = Bus::find($bus_id);
                    $busResult->status = 'free';
                 
                    $busResult->save();

                    $passengerResult         = User::find($passenger_id);
                    $passengerResult->status = 'open';
                    $passengerResult->save();

                    $bookingResult            = Booking::find($booking_id);
                    $bookingResult->status_id = 7;
                    $bookingResult->save();
                      
                    $general_id = $schedule_id_open;

                }else{
                    continue;
                }


              
            }

             $schedules         = Schedule::find($general_id);
             $schedules->status = "done";
             $schedules->save();
           
            $result["status"] = TRUE;
            $result["remarks"] = "Travel is Finished Successfully";


        }
         return response()->json($result, 200);
    }
}
