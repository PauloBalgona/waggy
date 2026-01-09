<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            // Luzon
            ['province' => 'Pampanga (Central Luzon)', 'region' => 'Luzon'],
            ['province' => 'Cavite (CALABARZON)', 'region' => 'Luzon'],
            ['province' => 'Laguna', 'region' => 'Luzon'],
            ['province' => 'Batangas', 'region' => 'Luzon'],
            ['province' => 'Bulacan', 'region' => 'Luzon'],
            ['province' => 'Nueva Ecija', 'region' => 'Luzon'],
            ['province' => 'Baguio City (Cordillera)', 'region' => 'Luzon'],
            ['province' => 'Ilocos Norte', 'region' => 'Luzon'],
            ['province' => 'La Union', 'region' => 'Luzon'],
            ['province' => 'Metro Manila', 'region' => 'Luzon'],
            ['province' => 'Quezon City (NCR)', 'region' => 'Luzon'],

            // Visayas
            ['province' => 'Cebu City', 'region' => 'Visayas'],
            ['province' => 'Bohol', 'region' => 'Visayas'],
            ['province' => 'Iloilo City', 'region' => 'Visayas'],
            ['province' => 'Bacolod City', 'region' => 'Visayas'],
            ['province' => 'Dumaguete (Negros Oriental)', 'region' => 'Visayas'],
            ['province' => 'Tacloban (Leyte)', 'region' => 'Visayas'],
            ['province' => 'Ormoc City', 'region' => 'Visayas'],
            ['province' => 'Roxas City (Capiz)', 'region' => 'Visayas'],
            ['province' => 'Samar', 'region' => 'Visayas'],
            ['province' => 'Aklan', 'region' => 'Visayas'],

            // Mindanao
            ['province' => 'Davao City', 'region' => 'Mindanao'],
            ['province' => 'Cagayan de Oro', 'region' => 'Mindanao'],
            ['province' => 'General Santos City', 'region' => 'Mindanao'],
            ['province' => 'Zamboanga City', 'region' => 'Mindanao'],
            ['province' => 'Butuan City', 'region' => 'Mindanao'],
            ['province' => 'Surigao', 'region' => 'Mindanao'],
            ['province' => 'Bukidnon', 'region' => 'Mindanao'],
            ['province' => 'Iligan City', 'region' => 'Mindanao'],
            ['province' => 'Tagum City', 'region' => 'Mindanao'],
            ['province' => 'Cotabato City', 'region' => 'Mindanao'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}