<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;

class ParkingFactorySeeder extends Seeder
{
    public function run(): void
    {
        Parking::factory()->count(20)->create();
    }
}
