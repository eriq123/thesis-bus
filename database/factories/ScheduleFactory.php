<?php

namespace Database\Factories;

use App\Models\Schedule;
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
        return [
            'bus_id' => rand(1, 50),
            'user_id' => rand(1, 50),
            'starting_point_id' => rand(1, 50),
            'destination_id' => rand(1, 50),
            'schedule_date' => Carbon::now()->format('M d Y'),
            'time_departure' => '5:30PM',
            'time_arrival' => '6:30PM',
        ];
    }
}
