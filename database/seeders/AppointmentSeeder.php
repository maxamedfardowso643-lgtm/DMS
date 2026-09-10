<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentStatusLog;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $dentists = Dentist::all();
        $services = Service::all();
        $statuses = ['completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'cancelled', 'no_show'];
        $faker = \Faker\Factory::create();

        $year = date('Y');

        for ($i = 1; $i <= 65; $i++) {
            $dentist = $dentists->random();
            $service = $services->random();
            $daysOffset = rand(-45, 10);
            $date = Carbon::today()->addDays($daysOffset);

            $hour = rand(9, 16);
            $minute = [0, 15, 30, 45][rand(0, 3)];
            $start = Carbon::parse($date->format('Y-m-d') . " $hour:$minute");
            $end = (clone $start)->addMinutes($service->duration_minutes);

            $status = $daysOffset > 0 ? $faker->randomElement(['booked', 'confirmed']) : $faker->randomElement($statuses);

            $appointment = Appointment::create([
                'appointment_no' => 'APT-' . $year . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentist->id,
                'service_id' => $service->id,
                'appointment_date' => $date->format('Y-m-d'),
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'source' => $faker->randomElement(['scheduled', 'scheduled', 'walk_in']),
                'status' => $status,
                'notes' => $faker->boolean(30) ? $faker->sentence() : null,
            ]);

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'from_status' => null,
                'to_status' => 'booked',
                'remarks' => 'Appointment created',
            ]);

            if ($status !== 'booked') {
                AppointmentStatusLog::create([
                    'appointment_id' => $appointment->id,
                    'from_status' => 'booked',
                    'to_status' => $status,
                    'remarks' => 'Status updated',
                ]);
            }
        }
    }
}
