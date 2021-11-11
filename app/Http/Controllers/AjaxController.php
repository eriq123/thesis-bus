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
    public function getTotalBooking()
    {

        $totalBooking      = Booking::get();
        $totalBooking      =$totalBooking->count();
        $totalBooking      = $totalBooking +1;
        $unPaidBooking     = Booking::where('status_id',1);
        $unPaidBooking     =$unPaidBooking->count();
        $unPaidBooking     = $unPaidBooking +1;
        $busOnTravel       = Bus::where('status','booked');
        $busOnTravel       = $busOnTravel->count();
        $totalPassengers   = User::where('role_id','4');
        $totalPassengers   = $totalPassengers->count();
        $totalPassengers   = $totalPassengers;
        $totalAdmin        = User::where('role_id','1');
        $totalAdmin        = $totalAdmin->count();
        $totalAdmin        = $totalAdmin;
        $totalDriver       = User::where('role_id','2');
        $totalDriver       = $totalDriver->count();
        $totalDriver       = $totalDriver;
        $totalConductor    = User::where('role_id','3');
        $totalConductor    = $totalConductor->count();
        $totalConductor    = $totalConductor;
        $result =array();
        $result['totalBooking']= $totalBooking;
        $result['unPaidBooking']= $unPaidBooking;
        $result['busOnTravel']= $busOnTravel;
        $result['totalPassengers']= $totalPassengers;
        $result['totalAdmin']= $totalAdmin;
        $result['totalDriver']= $totalDriver;
        $result['totalConductor']= $totalConductor;

        #create or update your data here
        return response()->json(['success'=>$result]);
      

    }

    public function monthlyProfit(){

       
        $january = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='January'"));

      
        for ($i = 0, $c = count($january); $i < $c; ++$i) {
            $january[$i] = (array) $january[$i];
        }
        if($january[0]['total_points'] =="" || $january[0]['total_points']== NULL){
            $january[0]['total_points'] = 0;

        }
        $january = $january[0]['total_points'];

        $feburay = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='Feburay'"));
        for ($i = 0, $c = count($feburay); $i < $c; ++$i) {
            $feburay[$i] = (array) $feburay[$i];
        }
        if($feburay[0]['total_points'] =="" || $feburay[0]['total_points']== NULL){
            $feburay[0]['total_points'] = 0;
        }
        $feburay = $feburay[0]['total_points'];

        $march = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='March'"));
        for ($i = 0, $c = count($march); $i < $c; ++$i) {
            $march[$i] = (array) $march[$i];
        }
        if($march[0]['total_points'] =="" || $march[0]['total_points']== NULL){
            $march[0]['total_points'] = 0;

        }
        $march = $march[0]['total_points'];

        $april = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='April'"));

         for ($i = 0, $c = count($april); $i < $c; ++$i) {
            $april[$i] = (array) $april[$i];
        }
        if($april[0]['total_points'] =="" || $april[0]['total_points']== NULL){
            $april[0]['total_points'] = 0;

        }
        $april = $april[0]['total_points'];

        $may = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='May'"));
        for ($i = 0, $c = count($may); $i < $c; ++$i) {
            $may[$i] = (array) $may[$i];
        }
        if($may[0]['total_points'] =="" || $may[0]['total_points']== NULL){
            $may[0]['total_points'] = 0;

        }
        $may = $may[0]['total_points'];
        $june = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='June'"));
        for ($i = 0, $c = count($june); $i < $c; ++$i) {
            $june[$i] = (array) $june[$i];
        }
        if($june[0]['total_points'] =="" || $june[0]['total_points']== NULL){
            $june[0]['total_points'] = 0;

        }
        $june = $june[0]['total_points'];
        $july = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='July'"));
        for ($i = 0, $c = count($july); $i < $c; ++$i) {
            $july[$i] = (array) $july[$i];
        }
        if($july[0]['total_points'] =="" || $july[0]['total_points']== NULL){
            $july[0]['total_points'] = 0;

        }
        $july = $july[0]['total_points'];
        $august = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='August'"));
        for ($i = 0, $c = count($august); $i < $c; ++$i) {
            $august[$i] = (array) $august[$i];
        }
        if($august[0]['total_points'] =="" || $august[0]['total_points']== NULL){
            $august[0]['total_points'] = 0;

        }
        $august = $august[0]['total_points'];
        $september = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='September'"));
        for ($i = 0, $c = count($september); $i < $c; ++$i) {
            $september[$i] = (array) $september[$i];
        }
        if($september[0]['total_points'] =="" || $september[0]['total_points']== NULL){
            $september[0]['total_points'] = 0;

        }
        $september = $september[0]['total_points'];
        $october = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='October'"));
        for ($i = 0, $c = count($october); $i < $c; ++$i) {
            $october[$i] = (array) $october[$i];
        }
        if($october[0]['total_points'] =="" || $october[0]['total_points']== NULL){
            $october[0]['total_points'] = 0;

        }
        $october = $october[0]['total_points'];
        $november = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='November'"));
        for ($i = 0, $c = count($november); $i < $c; ++$i) {
            $november[$i] = (array) $november[$i];
        }
        if($november[0]['total_points'] =="" || $november[0]['total_points']== NULL){
            $november[0]['total_points'] = 0;

        }
        $november = $november[0]['total_points'];
        $december = DB::select( DB::raw(" SELECT SUM(b.grand_total) as total_points  FROM `bookings` AS b INNER JOIN schedules AS s ON b.schedule_id = s.id WHERE monthname(s.schedule_date)='December'"));
        for ($i = 0, $c = count($december); $i < $c; ++$i) {
            $december[$i] = (array) $december[$i];
        }
        if($december[0]['total_points'] =="" || $december[0]['total_points']== NULL){
            $december[0]['total_points'] = 0;

        }
        $december = $december[0]['total_points'];






        $result = array();

        $result['january']=$january;
        $result['feburay']=$feburay;
        $result['march']=$march;
        $result['april']=$april;
        $result['may']=$may;
        $result['june']=$june;
        $result['july']=$july;
        $result['august']=$august;
        $result['september']=$september;
        $result['october']=$october;
        $result['november']=$november;
        $result['december']=$december;


         #create or update your data here
        return response()->json(['success'=>$result]);
    }

   

     
}
