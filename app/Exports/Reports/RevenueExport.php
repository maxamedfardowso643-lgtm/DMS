<?php

namespace App\Exports\Reports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $payments)
    {
    }

    public function headings(): array
    {
        return ['Payment No', 'Patient', 'Invoice No', 'Amount', 'Discount', 'Method', 'Payment Date', 'Received By'];
    }

    public function collection()
    {
        return $this->payments->map(fn ($p) => [
            $p->payment_no,
            $p->patient->full_name ?? '-',
            $p->invoice->invoice_no ?? '-',
            (float) $p->amount,
            (float) $p->discount_amount,
            ucfirst(str_replace('_', ' ', $p->method)),
            optional($p->payment_date)->format('Y-m-d'),
            $p->receivedBy->name ?? '-',
        ]);
    }
}
