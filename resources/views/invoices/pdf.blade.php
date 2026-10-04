<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f4f4f4; }
        .text-end { text-align: right; }
        .totals td { border: none; }
        .badge { padding: 3px 8px; border-radius: 4px; color: #fff; background: #6c757d; }
        .header img { height: 90px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        @php $logo = \App\Models\Setting::get('clinic_logo'); @endphp
        @if ($logo)
            <img src="{{ public_path('storage/' . $logo) }}" alt="Logo">
        @endif
        <h2>{{ \App\Models\Setting::get('clinic_name', config('app.name')) }}</h2>
        <p>{{ \App\Models\Setting::get('clinic_address') }} | {{ \App\Models\Setting::get('clinic_phone') }}</p>
        <h3>INVOICE {{ $invoice->invoice_no }}</h3>
    </div>

    <table class="totals">
        <tr>
            <td><strong>Patient:</strong> {{ $invoice->patient->full_name ?? '-' }} ({{ $invoice->patient->patient_code ?? '-' }})</td>
            <td class="text-end"><strong>Issue Date:</strong> {{ $invoice->issue_date->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td><strong>Phone:</strong> {{ $invoice->patient->phone ?? '-' }}</td>
            <td class="text-end"><strong>Due Date:</strong> {{ $invoice->due_date?->format('Y-m-d') ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr><th>Description</th><th>Tooth #</th><th>Qty</th><th>Unit Price</th><th>Discount</th><th>Tax</th><th>Total</th></tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->tooth_number ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-end">{{ number_format($item->discount_amount, 2) }}</td>
                    <td class="text-end">{{ number_format($item->tax_amount, 2) }}</td>
                    <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($invoice->notes)
        <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
    @endif

    <p style="margin-top: 40px; text-align: center; color: #888;">Thank you for choosing {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}.</p>
</body>
</html>
