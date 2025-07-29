<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;

class ParkingSeeder extends Seeder
{
    public function run(): void
    {
        if (Parking::count() > 0) {
            return;
        }

        $data = [
            [
                'name' => 'Seeker Parking Centro',
                'address' => 'Av. Corrientes 123, CABA',
                'latitude' => -34.6037345,
                'longitude' => -58.3815704,
            ],
            [
                'name' => 'Seeker Parking Obelisco',
                'address' => 'Av. 9 de Julio 100, CABA',
                'latitude' => -34.603263,
                'longitude' => -58.38162,
            ],
            [
                'name' => 'Seeker Parking Palermo',
                'address' => 'Av. Scalabrini Ortiz 1500, CABA',
                'latitude' => -34.5895,
                'longitude' => -58.3975,
            ],
            [
                'name' => 'Seeker Parking Recoleta',
                'address' => 'Arenales 2000, CABA',
                'latitude' => -34.5899,
                'longitude' => -58.3970,
            ],
            [
                'name' => 'Seeker Parking Puerto Madero',
                'address' => 'Alicia Moreau de Justo 800, CABA',
                'latitude' => -34.6069,
                'longitude' => -58.3625,
            ],
        ];

        foreach ($data as $p) {
            Parking::create($p);
        }
    }
}
