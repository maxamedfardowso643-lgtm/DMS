<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Ledger — {{ $patient->full_name }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, sans-serif; background: #eef0f6; padding: 2rem 1rem; color: #1a2035; margin: 0; }
        .sheet { max-width: 900px; margin: 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 10px 40px rgba(20,20,50,.1); padding: 2.5rem; }
        .sheet-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.8rem; }
        .brand { display: flex; align-items: center; gap: .8rem; }
        .brand .mark { width: 46px; height: 46px; border-radius: 12px; background: #4f46e5; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 1.3rem; overflow: hidden; }
        .brand .mark img { width: 100%; height: 100%; object-fit: cover; }
        .brand .name { font-weight: 800; font-size: 1.15rem; }
        .sheet-head .title { text-align: right; }
        .sheet-head .title h2 { font-weight: 800; font-size: 1.05rem; letter-spacing: .05em; margin: 0; }
        .sheet-head .title small { color: #9ca3af; font-size: .75rem; }

        .info-bar { display: flex; background: #f3f5fb; border-radius: 12px; overflow: hidden; margin-bottom: 1.8rem; }
        .info-bar > div { flex: 1; padding: 1rem 1.3rem; }
        .info-bar > div + div { border-left: 1px solid #e6e9f2; }
        .info-bar .label { font-size: .68rem; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; margin-bottom: .2rem; }
        .info-bar .value { font-weight: 700; font-size: .95rem; }
        .info-bar .balance .value { color: #ef4444; font-size: 1.15rem; }

        .section-title { display: flex; align-items: center; gap: .6rem; font-weight: 700; font-size: .95rem; margin-bottom: .9rem; }
        .section-title .bar { width: 4px; height: 16px; background: #4f46e5; border-radius: 2px; }

        table { width: 100%; border-collapse: collapse; }
        thead th { text-align: left; font-size: .7rem; text-transform: uppercase; letter-spacing: .04em; color: #9ca3af; padding: .6rem .5rem; border-bottom: 2px solid #f1f2f6; }
        thead th.num { text-align: right; }
        tbody td { padding: .75rem .5rem; font-size: .85rem; border-bottom: 1px solid #f1f2f6; }
        tbody td.num { text-align: right; font-weight: 600; }
        .due-amt { color: #ef4444; }
        .paid-amt { color: #10b981; }
        tfoot td { padding: .85rem .5rem; font-weight: 800; border-top: 2px solid #1a2035; font-size: .85rem; }
        tfoot td.num { text-align: right; }

        .print-bar { max-width: 900px; margin: 0 auto 1rem; text-align: right; }
        .print-bar button { background: #4f46e5; color: #fff; border: none; padding: .55rem 1.1rem; border-radius: 8px; font-weight: 600; font-size: .85rem; cursor: pointer; }
        .foot-note { text-align: center; color: #9ca3af; font-size: .78rem; margin-top: 2rem; }
        @media print { .print-bar { display: none; } body { background: #fff; padding: 0; } .sheet { box-shadow: none; border-radius: 0; } }
    </style>
</head>
<body>
    <div class="print-bar"><button onclick="window.print()">🖨 Print</button></div>
    <div class="sheet">
        <div class="sheet-head">
            <div class="brand">
                <div class="mark">
                    @php $logo = \App\Models\Setting::get('clinic_logo'); @endphp
                    @if ($logo)<img src="{{ asset('storage/' . $logo) }}">@else M @endif
                </div>
                <div class="name">{{ \App\Models\Setting::get('clinic_name', config('app.name')) }}</div>
            </div>
            <div class="title">
                <h2>PATIENT LEDGER</h2>
                <small>Generated on: {{ now()->format('d M Y H:i') }}</small>
            </div>
        </div>

        <div class="info-bar">
            <div>
                <div class="label">Name</div>
                <div class="value">{{ $patient->full_name }}</div>
            </div>
            <div>
                <div class="label">ID</div>
                <div class="value">{{ $patient->patient_code }}</div>
            </div>
            <div>
                <div class="label">Phone</div>
                <div class="value">{{ $patient->phone ?? '-' }}</div>
            </div>
            <div class="balance">
                <div class="label">Current Balance</div>
                <div class="value">${{ number_format($transactions->last()['balance'] ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="section-title"><span class="bar"></span> Transaction History</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="num">Amount Due</th>
                    <th class="num">Amount Paid</th>
                    <th class="num">Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $t)
                    <tr>
                        <td>{{ $t['date']->format('d M Y') }}</td>
                        <td>{{ $t['description'] }}</td>
                        <td class="num due-amt">{{ $t['due'] ? '$' . number_format($t['due'], 2) : '' }}</td>
                        <td class="num paid-amt">{{ $t['paid'] ? '$' . number_format($t['paid'], 2) : '' }}</td>
                        <td class="num">${{ number_format($t['balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:#9ca3af;padding:2rem;">No transactions recorded yet.</td></tr>
                @endforelse
            </tbody>
            @if ($transactions->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="2">TOTALS</td>
                    <td class="num">${{ number_format($totalDue, 2) }}</td>
                    <td class="num">${{ number_format($totalPaid, 2) }}</td>
                    <td class="num">${{ number_format($transactions->last()['balance'], 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>

        <p class="foot-note">This statement was generated by {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}.</p>
    </div>
</body>
</html>
