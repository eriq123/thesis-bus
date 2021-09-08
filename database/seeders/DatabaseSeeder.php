<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->insert(
            [

                [
                    'name' => 'Admin',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Bus Driver',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Bus Conductor',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'Passenger',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]
        );

        DB::table('status')->insert(
            [
                [
                    'title' => 'Open',
                    'class' => 'info',
                    'following_id' => NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => 'Paid',
                    'class' => 'primary',
                    'following_id' => NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => 'Verified',
                    'class' => 'success',
                    'following_id' => NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => 'Closed',
                    'class' => 'secondary',
                    'following_id' => NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'title' => 'Cancelled',
                    'class' => 'secondary',
                    'following_id' => NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]
        );

        foreach(range(1, 3) as $iteration) {
            Status::find($iteration)->update([
                'following_id' => ++$iteration
            ]);
        }

        User::factory()->count(50)->create();
        Bus::factory()->count(50)->create();

        BusRoute::factory()->count(10)->create();

        $busRoutes = BusRoute::all();

        foreach($busRoutes as $start) {
            foreach($busRoutes as $destination) {
                if($start->id !== $destination->id) {
                    Schedule::factory()->count(10)->create([
                        'starting_point_id' => $start->id,
                        'destination_id' => $destination->id,
                    ]);
                }
            }
        }

        Booking::factory()->count(20)->create();
    }
}
