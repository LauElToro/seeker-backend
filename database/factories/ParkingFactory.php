<?php

namespace Database\Factories;

use App\Models\Parking;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParkingFactory extends Factory
{
    protected $model = Parking::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Parking',
            'address' => $this->faker->streetAddress . ', CABA',
            'latitude' => $this->faker->randomFloat(6, -34.65, -34.55),
            'longitude' => $this->faker->randomFloat(6, -58.45, -58.33),
        ];
    }
}
