<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $schedule_id = $this->faker->randomElement(Schedule::pluck('id')->toArray());
        $schedule = Schedule::find($schedule_id);
        $quantity = rand(1, 5);

        return [
            'user_id' => $this->faker->randomElement(User::where('role_id', 4)->pluck('id')->toArray()),
            'schedule_id' => $schedule_id,
            'bus_id' => $this->faker->randomElement(Bus::pluck('id')->toArray()),
            'driver_id' => $this->faker->randomElement(User::where('role_id', 2)->pluck('id')->toArray()),
            'conductor_id' => $this->faker->randomElement(User::where('role_id', 3)->pluck('id')->toArray()),
            'fare_amount' => $schedule->fare,
            'quantity' => $quantity,
            'grand_total' => $quantity * $schedule->fare,
            'status' => 'Open'
        ];
    }
}
