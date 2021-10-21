<?php

namespace Database\Factories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
class BusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plate_number' => strtoupper($this->faker->lexify('???')) . '-' . rand(100 , 9999),
            'type' => Arr::random(['Mini Bus', 'Normal Bus']),
            'capacity' => rand(30,50),
        ];
    }
}
