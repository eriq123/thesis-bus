<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use DB;
class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hourly:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
       
        $user=DB::table('users')->join('bookings', function($join)
                            {
                                $join->on('users.id', '=', 'bookings.user_id');
                            })->join('schedules', function($join)
                            {
                                $join->on('schedules.id', '=', 'bookings.schedule_id');
                            })->join('buses', function($join)
                            {
                                $join->on('buses.id', '=', 'schedules.bus_id');
                            })
                            ->where('schedules.status', '=', 'open')
                            ->orWhere('schedules.status', '=', 'travel')
                            ->get();

        $current_date = Carbon::now();
        //Get date and time
    
       $newDateTime = $current_date->toDateTimeString();
       $newDateTime = explode(" ", $newDateTime);
       $date_now    = $newDateTime[0];
       $newTime     = date('h:i A', strtotime($current_date->toDateTimeString()));
       $newTime     = explode(" ", $newTime);
       $time_now    = $newTime[0];
       $ampm        = $newTime[1];

       $time_now    = explode(":",$time_now);
       $hour        = $time_now[0];
       $min         = $time_now[1];

       
       echo $hour;


        $array = json_decode(json_encode($user), true);

        foreach($array as $item){


            if($item['phone_number'] != "" && !empty($item['phone_number']) && $item['phone_number'] != NULL)
             {
                
                 $tempDate = explode(":",$item['time_departure']);
                 $tempDateHour = rtrim($tempDate[0]);
                 $tempDateTime =  ltrim($tempDate[1]);

                 $tempDateTime = explode(" ",$tempDateTime);
                 $tempMins     = $tempDateTime[0];
                 $tempAmPm     = $tempDateTime[1]; 
                  // echo "<pre>";print_r($tempDateTime);echo "</pre>";


                 if($item['schedule_date']>= $date_now){

                    if($tempAmPm == $ampm){

                    
                    }

                 }else{
                   
                 }
       
             }
        }
       
        $this->info('Hourly Update has been send successfully');
    }
}
