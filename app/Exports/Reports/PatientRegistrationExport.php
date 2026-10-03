<?php

namespace App\Exports\Reports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PatientRegistrationExport implements FromCollection, WithHeadings
{
    public function __construct(protected Builder $query)
    {
    }

    public function headings(): array
    {
        return ['Patient ID', 'Full Name', 'Gender', 'Age', 'Phone', 'Email', 'Registration Date', 'Patient Type', 'Assigned Dentist'];
    }

    public function collection()
    {
        return $this->query->get()->map(fn ($p) => [
            $p->patient_code,
            $p->full_name,
            ucfirst($p->gender ?? '-'),
            $p->date_of_birth ? $p->date_of_birth->age : '-',
            $p->phone ?? '-',
            $p->email ?? '-',
            $p->created_at->format('Y-m-d'),
            $p->appointments_count > 0 ? 'Returning' : 'New',
            $p->assigned_dentist_name ?? '-',
        ]);
    }
}
