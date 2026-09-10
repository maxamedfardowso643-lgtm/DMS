<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Dental Check-up', 'category' => 'preventive', 'price' => 15, 'duration_minutes' => 20],
            ['name' => 'Teeth Cleaning (Scaling)', 'category' => 'preventive', 'price' => 30, 'duration_minutes' => 30],
            ['name' => 'Tooth Filling', 'category' => 'restorative', 'price' => 40, 'duration_minutes' => 45],
            ['name' => 'Tooth Extraction', 'category' => 'surgical', 'price' => 35, 'duration_minutes' => 30],
            ['name' => 'Root Canal Treatment', 'category' => 'restorative', 'price' => 120, 'duration_minutes' => 90],
            ['name' => 'Dental Crown', 'category' => 'restorative', 'price' => 150, 'duration_minutes' => 60],
            ['name' => 'Teeth Whitening', 'category' => 'cosmetic', 'price' => 80, 'duration_minutes' => 45],
            ['name' => 'Braces Consultation', 'category' => 'orthodontic', 'price' => 25, 'duration_minutes' => 30],
            ['name' => 'Dental Implant', 'category' => 'surgical', 'price' => 400, 'duration_minutes' => 120],
            ['name' => 'X-Ray Imaging', 'category' => 'diagnostic', 'price' => 20, 'duration_minutes' => 15],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service + ['is_active' => true]);
        }
    }
}
