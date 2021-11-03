<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Booking;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
     
        $user->token = $user->createToken('Access_token')->plainTextToken;

        return response()->json($user, 200);
    }

    public function logins(Request $request)
    {
        
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
         // die("I am here.. LA ALA LALALA A");

        // echo "<pre>";print_r($request->item_id);echo "</pre>";
        // die("I am here.. LA ALA LALALA A");

    //     $encodedImage = $request->proof_image;


    //     $encodedImage = base64_decode($encodedImage);

    //      if(!$request->hasFile('proof_image')) {
    //         $result["status"] = FALSE;
    //         $result["remarks"] = "File Not Found";
    //         return response()->json($result, 400);
        
    // }else{
    //      if($encodedImage !=""){
    //          $path = $request->file('proof_image')->storeAs('public',"test.png");
    //         $result["status"] = TRUE;
    //         $result["remarks"] = "Image Uploaded Successfully";
    //           return response()->json($result, 200);
    //     }else{
    //         $result["status"] = FALSE;
    //         $result["remarks"] = "Image Uploading Failed";
    //       return response()->json($result, 400);
    //     }
    //     // $imageTitle = "myImage";
    //     // $imageLocation = "images/$imageTitle.jpg";
    //     //   $result["status"] = TRUE;
    //     //     $result["remarks"] = "Image Uploaded Successfully";
       

    // }
 
       

    public function register(Request $request)
    {
        $user = $this->authRepository->register($request);
        $user = User::find($user->id);
        $user->token = $user->createToken('Access_token')->plainTextToken;

        return response()->json($user, 200);
    }

    public function google(Request $request)
    {
        $user = $this->authRepository->google($request);
        $user->token = $user->createToken('Access_token')->plainTextToken;

        return response()->json($user, 200);
    }

    public function logout(Request $request)
    {
        $user = User::find($request->id);
        $user->tokens()->where('id', $request->token)->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
}
