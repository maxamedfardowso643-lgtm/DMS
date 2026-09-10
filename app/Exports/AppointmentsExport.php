<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppointmentsExport implements FromCollection, WithHeadings
{
    public function __construct(protected string $from, protected string $to)
    {
    }

    public function headings(): array
    {
        return ['Appointment No', 'Patient', 'Dentist', 'Service', 'Date', 'Start Time', 'Status'];
    }

    public function collection()
    {
        return Appointment::with(['patient', 'dentist.user', 'service'])
            ->whereBetween('appointment_date', [$this->from, $this->to])
            ->get()
            ->map(fn ($a) => [
                $a->appointment_no,
                $a->patient->full_name,
                $a->dentist->user->name ?? '-',
                $a->service->name ?? '-',
                $a->appointment_date->format('Y-m-d'),
                substr($a->start_time, 0, 5),
                $a->status,
            ]);
    }
}
