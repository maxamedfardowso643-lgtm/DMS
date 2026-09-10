<?php

namespace Database\Seeders;

use App\Models\Dentist;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Each dentist works 5 days a week, but on a different shift.
        $shifts = [
            ['start_time' => '08:00', 'end_time' => '14:00'], // morning shift
            ['start_time' => '14:00', 'end_time' => '20:00'], // afternoon/evening shift
            ['start_time' => '09:00', 'end_time' => '17:00'], // full day shift
        ];

        Dentist::all()->values()->each(function (Dentist $dentist, int $index) use ($shifts) {
            $shift = $shifts[$index % count($shifts)];

            // Sunday(0) - Thursday(4): typical working week
            foreach (range(0, 4) as $day) {
                Schedule::updateOrCreate(
                    ['dentist_id' => $dentist->id, 'type' => 'weekly', 'day_of_week' => $day],
                    [
                        'start_time' => $shift['start_time'],
                        'end_time' => $shift['end_time'],
                        'is_active' => true,
                    ]
                );
            }
        });
    }
}
