<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $payment->payment_no }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #eef0f6; font-family: 'Inter', -apple-system, sans-serif; padding: 2rem 1rem; }
        .receipt {
            max-width: 480px; margin: 0 auto; background: #fff; border-radius: 18px;
            box-shadow: 0 10px 40px rgba(20,20,50,.12); overflow: hidden;
        }
        .receipt-head {
            background: linear-gradient(135deg,#6366f1,#4338ca); color: #fff; padding: 2rem 1.75rem 1.5rem; text-align: center;
        }
        .receipt-head .logo { width: 56px; height: 56px; border-radius: 14px; background: rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; margin: 0 auto .8rem; font-size: 1.5rem; overflow:hidden; }
        .receipt-head h4 { font-weight: 800; margin-bottom: .2rem; }
        .receipt-head small { opacity: .85; }
        .receipt-body { padding: 1.75rem; }
        .amount-block { text-align: center; margin-bottom: 1.5rem; }
        .amount-block .amt { font-size: 2.2rem; font-weight: 800; color: #111827; }
        .amount-block .status { display:inline-block; margin-top:.4rem; background:#ecfdf5; color:#059669; font-weight:700; font-size:.75rem; padding:.3rem .8rem; border-radius:999px; }
        .row-line { display:flex; justify-content:space-between; padding: .55rem 0; border-bottom: 1px dashed #e5e7eb; font-size: .875rem; }
        .row-line span:first-child { color: #6b7280; }
        .row-line span:last-child { font-weight: 600; color: #111827; }
        .receipt-foot { text-align:center; padding: 1.25rem; color: #9ca3af; font-size: .78rem; border-top: 1px solid #f1f2f6; }
        .print-bar { max-width: 480px; margin: 0 auto 1rem; text-align: right; }
        @media print { .print-bar { display: none; } body { background: #fff; padding: 0; } .receipt { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="print-bar">
        <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
    </div>
    <div class="receipt">
        <div class="receipt-head">
            <div class="logo">
                @php $logo = \App\Models\Setting::get('clinic_logo'); @endphp
                @if ($logo)
                    <img src="{{ asset('storage/' . $logo) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i class="fas fa-tooth"></i>
                @endif
            </div>
            <h4>{{ \App\Models\Setting::get('clinic_name', config('app.name')) }}</h4>
            <small>{{ \App\Models\Setting::get('clinic_phone') }} @if(\App\Models\Setting::get('clinic_email')) &middot; {{ \App\Models\Setting::get('clinic_email') }} @endif</small>
        </div>
        <div class="receipt-body">
            <div class="amount-block">
                <div class="text-muted" style="font-size:.8rem;">Amount Paid</div>
                <div class="amt">${{ number_format($payment->amount, 2) }}</div>
                <span class="status"><i class="fas fa-check-circle me-1"></i> Payment Successful</span>
            </div>

            <div class="row-line"><span>Receipt No.</span><span>{{ $payment->payment_no }}</span></div>
            <div class="row-line"><span>Date</span><span>{{ $payment->payment_date->format('Y-m-d') }} {{ $payment->created_at->format('H:i') }}</span></div>
            <div class="row-line"><span>Patient</span><span>{{ $payment->patient->full_name }}</span></div>
            <div class="row-line"><span>Patient ID</span><span>{{ $payment->patient->patient_code }}</span></div>
            <div class="row-line"><span>Invoice #</span><span>{{ $payment->invoice->invoice_no ?? '-' }}</span></div>
            @if ($payment->discount_amount > 0)
                <div class="row-line"><span>Discount Applied</span><span>${{ number_format($payment->discount_amount, 2) }}</span></div>
            @endif
            <div class="row-line"><span>Payment Method</span><span>{{ $payment->method }}</span></div>
            @if ($payment->sender_phone)
                <div class="row-line"><span>Sender Phone</span><span>{{ $payment->sender_phone }}</span></div>
            @endif
            @if ($payment->reference_no)
                <div class="row-line"><span>Reference No.</span><span>{{ $payment->reference_no }}</span></div>
            @endif
            <div class="row-line"><span>Received By</span><span>{{ $payment->receivedBy->name ?? '-' }}</span></div>
        </div>
        <div class="receipt-foot">
            Thank you for choosing {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}.<br>
            This is a computer-generated receipt.
        </div>
    </div>
</body>
</html>
