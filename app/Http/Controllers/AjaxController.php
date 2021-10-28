<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\User;
use App\Repositories\ReportRepository;
use Illuminate\Support\Facades\DB;


class AjaxController extends Controller
{
    //


    public function fetchData(Request $request)
    {
        $data = $request->all();
        $from = $request->from; 
        $to   = $request->to;

        #create or update your data here
       $bookings = DB::table('bookings')->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')->join('bus_routes', 'bus_routes.id', '=', 'schedules.starting_point_id')
           ->whereBetween('bookings.updated_at', [$from, $to])->where('bookings.status_id','=',2)->orWhere('bookings.status_id','=',3)
            ->select('bookings.*', 'schedules.*', 'bus_routes.*')
           ->get();  
        for ($i = 0, $c = count($bookings); $i < $c; ++$i) {
            $bookings[$i] = (array) $bookings[$i];
        }

        return response()->json(['success'=>$bookings]);
    }


    public function getLocation(Request $request)
    {
        $data = $request->all();

        $bus = DB::table('buses')->where('id',$request->bus_id)->get();
       
        $busData =array();

        $busData['id']=$bus[0]->id;
        $busData['long']=$bus[0]->long;
        $busData['lat']=$bus[0]->lat;
  
        #create or update your data here
        return response()->json(['success'=>$busData]);
      

    }

   

     
}
