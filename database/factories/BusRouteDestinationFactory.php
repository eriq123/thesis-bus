<?php

namespace Database\Factories;

use App\Models\BusRouteDestination;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusRouteDestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusRouteDestination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
        ];
    }
}
