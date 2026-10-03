<?php

namespace App\Exports\Reports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppointmentSummaryExport implements FromCollection, WithHeadings
{
    public function __construct(protected Builder $query)
    {
    }

    public function headings(): array
    {
        return ['Appointment No', 'Patient', 'Dentist', 'Service', 'Date', 'Start Time', 'Status', 'Source'];
    }

    public function collection()
    {
        return $this->query->get()->map(fn ($a) => [
            $a->appointment_no,
            $a->patient->full_name ?? '-',
            $a->dentist->user->name ?? '-',
            $a->service->name ?? '-',
            $a->appointment_date->format('Y-m-d'),
            substr($a->start_time, 0, 5),
            ucfirst(str_replace('_', ' ', $a->status)),
            $a->source === 'walk_in' ? 'Walk-in' : 'Scheduled',
        ]);
    }
}
