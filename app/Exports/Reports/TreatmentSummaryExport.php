<?php

namespace App\Exports\Reports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TreatmentSummaryExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $rows)
    {
    }

    public function headings(): array
    {
        return ['Treatment', 'Category', 'Total', 'Patients', 'Completed', 'Pending', 'Cancelled', 'Revenue'];
    }

    public function collection()
    {
        return $this->rows->map(fn ($r) => [
            $r->name,
            $r->category ?? '-',
            $r->total,
            $r->patients,
            $r->completed,
            $r->pending,
            $r->cancelled,
            number_format($r->revenue, 2),
        ]);
    }
}
