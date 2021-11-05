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
        $data  = $request->all();
        $from  = $request->from; 
        $to    = $request->to;
        $_type = $request->_type;
        $_id = $request->_id;

        if($_type == 'a'){

              $results = DB::select( DB::raw("SELECT b.*,s.*,r.*,st.* FROM bookings AS b INNER JOIN schedules AS s On b.schedule_id = s.id INNER JOIN bus_routes AS r ON s.starting_point_id = r.id INNER JOIN status AS st On b.status_id = st.id   WHERE s.schedule_date between '$from' and '$to' AND ( b.status_id = 2 OR b.status_id =3 OR b.status_id = 6)"));
        }elseif($_type == 'p'){
             $results = DB::select( DB::raw("SELECT b.*,s.*,r.*,st.* FROM bookings AS b INNER JOIN schedules AS s On b.schedule_id = s.id INNER JOIN bus_routes AS r ON s.starting_point_id = r.id INNER JOIN status AS st On b.status_id = st.id   WHERE s.schedule_date between '$from' and '$to' AND ( b.status_id = 2 OR b.status_id =3 OR b.status_id = 6) AND (b.user_id == '$_id')"));
        }

        #create or update your data here
       // $bookings = DB::table('bookings')->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')->join('bus_routes', 'bus_routes.id', '=', 'schedules.starting_point_id')
       //     ->whereBetween('schedules.schedule_date', [$from, $to])->where('bookings.status_id','=',2)->orWhere('bookings.status_id','=',3)
       //      ->select('bookings.*', 'schedules.*', 'bus_routes.*')
       //     ->get();  

         
     

        for ($i = 0, $c = count($results); $i < $c; ++$i) {
            $results[$i] = (array) $results[$i];
        }

        return response()->json(['success'=>$results]);
    }

    public function approveBooking(Request $request){
      
            
        $data = $request->all();
        $itemId = $request->itemId; 
       
        $booking = Booking::find($itemId);
        $booking->status_id = 3;

          $booking->save();
        $user_number = User::find($booking->user_id)->phone_number;
        $message = "Your Ticket is paid for Bus route : ".$booking->user_name.'-'.$booking->schedule->starting_point->name." And approved by Elizabeth Transport";
        $apicode = env('API_CODE','');
        $apipassword = env('API_PASSWORD','');
        $result = $this->itexmo($user_number,$message,$apicode, $apipassword);

    
      
        return response()->json(['success'=>$booking]);
       

    }
    public function itexmo($number,$message,$apicode,$passwd)
    {
             $url = 'https://www.itexmo.com/php_api/api.php';
             $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
             $param = array(
                 'http' => array(
                     'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                     'method'  => 'POST',
                     'content' => http_build_query($itexmo),
                 ),
             ); 
             $context  = stream_context_create($param);
             return file_get_contents($url, false, $context);
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
