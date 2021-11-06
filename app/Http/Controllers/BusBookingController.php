<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\User;
use App\Models\Bus;
use App\Repositories\BusBookingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\File;
use Illuminate\Database\Eloquent\Collection;
use Luigel\Paymongo\Facades\Paymongo;



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
        $this->busBookingRepository->updateStatus($request);
        return redirect()->route('buses.bookings.index')->withSuccess('Status updated Successfully!');
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
    public function updateStatusBookingSuccess($id)
    {  
        
        $booking     = Booking::find($id);
        $user_number = User::find($booking->user_id)->phone_number;
        $payment     = Paymongo::payment()->create([
                            'amount' => $booking->grand_total,
                            'currency' => 'PHP',
                            'description' => 'Booking ID: '.$id.', Customer Name: '.$booking->user_name.', Route: '.$booking->schedule->starting_point->name . " - " .$booking->schedule->destination->name,
                            'statement_descriptor' => 'Payment of bus fare through gcash',
                            'source' => [
                                'id' => $booking->payment_source_id,
                                'type' => 'source'
                            ]
                        ]);

         $booking->status_id = 2;
         $booking->save();
  
         $message = "You have Successfully paid for Bus route : ".$booking->user_name.'-'.$booking->schedule->starting_point->name;
         $result = $this->itexmo($user_number,$message,$_ENV['API_CODE'], $_ENV['API_PASSWORD']);

    
         if ($result == "")
         {
            echo "iTexMo: No response from server!!!
            Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.  
            Please CONTACT US for help. ";  
         }else if ($result == 0){
              return redirect()->route('buses.bookings.index')->withSuccess('Ticket Paid Successfully!');
            
          }else{   
                echo "Error Num ". $result . " was encountered!";
          }
          return redirect()->route('buses.bookings.index')->withSuccess('Ticket Paid Successfully!');
        
       
    }
    public function updateStatusBookingFail($id)
    {
       
        return redirect()->route('buses.bookings.index')->withSuccess('Ticket paying get an error. Please try again !');
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
 
    public function payingBooking(Request $request)
    {
        $id = $request->itemId;
        $amount  = $request->fare;
        $userId  = $request->userId;
        $phone   = $request->gcashInput;

        $success_url = url('/')."/buses/bookings/updateBookingSuccess/".$id;
        $fail_url = url('/')."/buses/bookings/updateBookingFail/".$id;

        $gcashSource = Paymongo::source()->create([
            'type' => 'gcash',
            'amount' => $amount,
            'currency' => 'PHP',
            'redirect' => [
                'success' => $success_url,
                'failed' => $fail_url
            ]
        ]);
        $booking = Booking::find($id);
        $user    = User::find($userId);
        $user->phone_number = $request->gcashInput;
        $user->save();
      
      
        $booking->payment_source_id = $gcashSource->id; 
        $booking->save();


        return redirect($gcashSource->redirect['checkout_url']);
         
       
    }

    public function payment(Request $request)
    {
       
         $id = $request->bookingItemId;
       
        $this->data['booking'] = Booking::find($id);
       
        $bus = Bus::find($this->data['booking']->bus_id);
       
     

        $allData = array();
        $allData['id']=$this->data['booking']->id;
        $allData['user_id']=$this->data['booking']->user_id;
        $allData['schedule_id']=$this->data['booking']->schedule_id;
        $allData['bus_id']=$this->data['booking']->bus_id;
        $allData['driver_id']=$this->data['booking']->driver_id;
        $allData['conductor_id']=$this->data['booking']->conductor_id;
        $allData['user_name']=$this->data['booking']->user_name;
        $allData['fare_amount']=$this->data['booking']->fare_amount;
        $allData['quantity']=$this->data['booking']->quantity;
        $allData['grand_total']=$this->data['booking']->grand_total;
        $allData['status_id']=$this->data['booking']->status_id;
        $allData['payment_source_id']=$this->data['booking']->payment_source_id;
        $allData['payment_image']=$this->data['booking']->payment_image;
       
        $allData['bus_gcash_number']=$bus->gcash_number;
       
         return view('admin.bus.payment.index', $allData); 
         return view('admin.bus.payment.index', $this->data['booking']); 
    
    }

    public function paymentProcessing(Request $request)
    {
     
       $filename       = $request->file('refernceProof')->getClientOriginalName();
       $id             = $request->itemId;
       $refernceNumber = $request->refernceNumber;
       $uPhoneNumber   = $request->gcashNumber;
       $userId         = $request->passengerId;


       

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newFileName =$refernceNumber.".".$ext;
       
        $path = $request->file('refernceProof')->storeAs('public',$newFileName);


        $booking = Booking::find($id);
        $booking->payment_source_id = $refernceNumber;
        $booking->payment_image     = $newFileName;
        $booking->status_id = 2;
        $booking->save();

        $user = User::find($userId);
        $user->gcash_number = $uPhoneNumber;
        $user->save();

        return redirect()->route('buses.bookings.index')->withSuccess('Ticket Paid Successfully ! Wait for Admin approval');

    
    }

    public function busLocation($id)
    {
        $this->data = $this->busBookingRepository->location();
        $this->data['booking'] = Booking::find($id);
      
        return view('admin.bus.booking.location', $this->data);
    }

  
    
}




