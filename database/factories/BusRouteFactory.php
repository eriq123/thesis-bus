<?php

namespace Database\Factories;

use App\Models\BusRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusRouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusRoute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'starting_point' => $this->faker->city,
            'destination' => $this->faker->city,
            'fare' => rand(100,2000),
        ];
    }
}
