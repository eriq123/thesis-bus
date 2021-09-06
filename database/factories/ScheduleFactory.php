<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $schedule_dates = [];
        $schedule_dates[] = Carbon::now()->format('Y-m-d');
        $schedule_dates[] = Carbon::now()->addDay()->format('Y-m-d');

        $departureTime = [
            '1:00 PM',
            '2:00 PM',
            '3:00 PM',
            '4:00 PM',
            '5:00 PM',
            '6:00 PM',
        ];
        $arrivalTime = [
            '2:00 PM',
            '3:00 PM',
            '4:00 PM',
            '5:00 PM',
            '6:00 PM',
            '7:00 PM',
        ];

        $timeRandomKey = array_rand($departureTime, 1);

        return [
            'bus_id' => $this->faker->randomElement(Bus::pluck('id')->toArray()),
            'driver_id' => $this->faker->randomElement(User::where('role_id', 2)->pluck('id')->toArray()),
            'conductor_id' => $this->faker->randomElement(User::where('role_id', 3)->pluck('id')->toArray()),
            'starting_point_id' => 1,
            'destination_id' => 1,
            'fare'=> rand(200, 999),
            'schedule_date' => $schedule_dates[array_rand($schedule_dates, 1)],
            'time_departure' => $departureTime[$timeRandomKey],
            'time_arrival' => $arrivalTime[$timeRandomKey],
        ];
    }
}
