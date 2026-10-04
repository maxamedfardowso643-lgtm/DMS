@extends('layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payments & Billing')
@section('breadcrumb')
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Payments</h3>
        <button class="btn btn-success btn-sm" onclick="openPaymentModal()"><i class="fas fa-plus"></i> Record Payment</button>
    </div>
    <div class="card-body">
        <table id="payments-table" class="table table-bordered table-striped w-100">
            <thead>
                <tr><th>Payment #</th><th>Invoice #</th><th>Patient</th><th>Date</th><th>Amount</th><th>Method</th><th>Type</th><th>Actions</th></tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Step 1: search patient --}}
                <div id="pay-step-search">
                    <label class="form-label">Search Patient</label>
                    <div class="patient-search-box">
                        <input type="text" id="pay_patient_search" class="form-control form-control-lg" placeholder="Search by name, patient ID, or mobile number..." autocomplete="off">
                        <div class="search-results" id="pay_patient_results"></div>
                    </div>
                    <p class="text-muted mt-2 mb-0" style="font-size:.82rem;">Start typing at least 2 characters to find a patient.</p>
                </div>

                {{-- Step 2: patient found -> show balance + payment form --}}
                <div id="pay-step-form" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center p-3 mb-3" style="background:var(--surface-2);border-radius:var(--radius);">
                        <div>
                            <div class="fw-bold" id="pay_patient_name" style="font-size:1.05rem;"></div>
                            <div class="text-muted small" id="pay_patient_code"></div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Total Outstanding</div>
                            <div class="fw-bold text-danger" id="pay_total_balance" style="font-size:1.3rem;"></div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="resetPaymentFlow()"><i class="fas fa-search"></i> Change</button>
                    </div>

                    <form id="payment-form">
                        <input type="hidden" id="pay_payment_id" name="payment_id" value="">
                        <input type="hidden" name="type" value="payment">
                        <div class="mb-3">
                            <label class="form-label">Invoice *</label>
                            <select name="invoice_id" id="pay_invoice_select" class="form-control" required></select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Date *</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount (optional)</label>
                                <input type="number" step="0.01" name="discount_amount" id="pay_discount" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Amount to Pay *</label>
                                <input type="number" step="0.01" name="amount" id="pay_amount" class="form-control" min="0.01" required>
                            </div>
                            <div class="col-md-12 mb-3" id="pay_summary" style="display:none;">
                                <div class="d-flex flex-wrap gap-3 p-2 px-3" style="background:var(--surface-2);border-radius:var(--radius);font-size:.85rem;">
                                    <div>Invoice Balance: <b id="pay_sum_balance">$0.00</b></div>
                                    <div>Discount: <b id="pay_sum_discount">$0.00</b></div>
                                    <div>Amount Due: <b id="pay_sum_due">$0.00</b></div>
                                    <div>Remaining After Payment: <b id="pay_sum_remaining">$0.00</b></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Method *</label>
                                <select name="payment_method_id" id="pay_method_select" class="form-control" required>
                                    @foreach ($paymentMethods as $m)
                                        <option value="{{ $m->id }}" data-code="{{ $m->code }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="pay_sender_wrap">
                                <label class="form-label">Sender Phone Number</label>
                                <input type="text" name="sender_phone" class="form-control" placeholder="e.g. 252612345678">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Reference / Transaction No.</label>
                                <input type="text" name="reference_no" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" id="pay-footer" style="display:none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="pay-submit-btn" onclick="submitPayment()"><i class="fas fa-check"></i> Confirm &amp; Print Receipt</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let paymentsTable;
let selectedPatientInvoices = [];

$(function () {
    paymentsTable = $('#payments-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route('payments.index') }}', type: 'GET' },
        columns: [
            { data: 'payment_no' },
            { data: 'invoice_no' },
            { data: 'patient' },
            { data: 'payment_date' },
            { data: 'amount' },
            { data: 'method' },
            { data: 'type' },
            {
                data: 'id', orderable: false, searchable: false,
                render: (id) => `
                    <a href="/payments/${id}/receipt" target="_blank" class="btn btn-secondary btn-xs"><i class="fas fa-receipt"></i></a>
                    <button class="btn btn-warning btn-xs" onclick="openEditPayment(${id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-xs" onclick="deletePayment(${id})"><i class="fas fa-trash"></i></button>
                `
            }
        ]
    });

    initPatientSearch('#pay_patient_search', '#pay_patient_results', function (patient) {
        loadPatientOutstanding(patient.id);
    });

    // Choosing an invoice or entering a discount recalculates the amount to pay.
    $('#pay_invoice_select').on('change', fillAmountDue);
    $('#pay_discount').on('input', fillAmountDue);
    $('#pay_amount').on('input', updatePaymentSummary);

    $('#pay_method_select').on('change', toggleSenderField);
    toggleSenderField();

    @if ($preselectInvoiceId && $preselectPatientId)
        resetPaymentFlow();
        loadPatientOutstanding({{ $preselectPatientId }});
        $('#paymentModal').modal('show');
        setTimeout(() => $('#pay_invoice_select').val('{{ $preselectInvoiceId }}').trigger('change'), 400);
    @endif
});

function toggleSenderField() {
    const code = $('#pay_method_select option:selected').data('code');
    $('#pay_sender_wrap').toggle(code === 'edahab' || code === 'sahal' || code === 'bank_transfer');
}

const money = n => '$' + (+n || 0).toFixed(2);

function selectedInvoice() {
    return selectedPatientInvoices.find(i => i.id == $('#pay_invoice_select').val());
}

function amountDue() {
    const inv = selectedInvoice();
    if (!inv) return 0;
    const discount = Math.min(Math.max(+$('#pay_discount').val() || 0, 0), inv.balance);
    return Math.round((inv.balance - discount) * 100) / 100;
}

function fillAmountDue() {
    // When editing, the balance already excludes this payment, so keep the saved amount.
    if ($('#pay_payment_id').val()) { updatePaymentSummary(); return; }
    const due = amountDue();
    $('#pay_amount').val(due > 0 ? due.toFixed(2) : '').attr('max', due.toFixed(2));
    updatePaymentSummary();
}

function updatePaymentSummary() {
    const inv = selectedInvoice();
    $('#pay_summary').toggle(!!inv);
    if (!inv) return;
    const due = amountDue();
    const remaining = due - (+$('#pay_amount').val() || 0);
    $('#pay_sum_balance').text(money(inv.balance));
    $('#pay_sum_discount').text(money(inv.balance - due));
    $('#pay_sum_due').text(money(due));
    $('#pay_sum_remaining').text(money(Math.max(remaining, 0)))
        .toggleClass('text-danger', remaining > 0.004).toggleClass('text-success', remaining <= 0.004);
}

function openPaymentModal() {
    resetPaymentFlow();
    $('#paymentModalLabel').text('Record Payment');
    $('#pay-submit-btn').html('<i class="fas fa-check"></i> Confirm &amp; Print Receipt');
    $('#paymentModal').modal('show');
}

function resetPaymentFlow() {
    $('#pay_payment_id').val('');
    $('#pay_patient_search').val('');
    $('#pay-step-search').show();
    $('#pay-step-form').hide();
    $('#pay-footer').hide();
    $('#payment-form')[0]?.reset();
    $('#pay_amount').removeAttr('max');
    $('#pay_summary').hide();
}

function loadPatientOutstanding(patientId) {
    $.get(`/patients/${patientId}/outstanding`, function (res) {
        renderPatientAndInvoices(res.patient, res.invoices, res.total_balance);
        fillAmountDue();
        $('#pay-step-search').hide();
        $('#pay-step-form').show();
        $('#pay-footer').show();
    });
}

function renderPatientAndInvoices(patient, invoices, totalBalance) {
    selectedPatientInvoices = invoices;

    $('#pay_patient_name').text(patient.name);
    $('#pay_patient_code').text(patient.code + (patient.phone ? ' · ' + patient.phone : ''));
    $('#pay_total_balance').text('$' + (totalBalance ?? invoices.reduce((s, i) => s + i.balance, 0)).toFixed(2));

    const select = $('#pay_invoice_select');
    select.empty();
    if (!invoices.length) {
        select.append('<option value="">No outstanding invoices for this patient</option>');
    } else {
        invoices.forEach(inv => {
            select.append(`<option value="${inv.id}">${inv.invoice_no} — Balance: $${inv.balance.toFixed(2)} (${inv.issue_date})</option>`);
        });
    }
}

function openEditPayment(id) {
    $.get(`/payments/${id}/edit`, function (res) {
        resetPaymentFlow();
        renderPatientAndInvoices(res.patient, res.invoices, null);

        const p = res.payment;
        $('#pay_payment_id').val(p.id);
        $('#pay_invoice_select').val(p.invoice_id);
        $('input[name="payment_date"]').val(p.payment_date.substring(0, 10));
        $('#pay_discount').val(p.discount_amount);
        $('#pay_amount').val(p.amount);
        $('#pay_method_select').val(p.payment_method_id).trigger('change');
        $('input[name="sender_phone"]').val(p.sender_phone);
        $('input[name="reference_no"]').val(p.reference_no);
        $('textarea[name="notes"]').val(p.notes);
        updatePaymentSummary();

        $('#pay-step-search').hide();
        $('#pay-step-form').show();
        $('#pay-footer').show();
        $('#paymentModalLabel').text('Edit Payment');
        $('#pay-submit-btn').html('<i class="fas fa-check"></i> Update Payment');
        $('#paymentModal').modal('show');
    });
}

function submitPayment() {
    if (!$('#pay_invoice_select').val()) {
        toastr.error('This patient has no outstanding invoice to pay.');
        return;
    }
    if (!$('#pay_payment_id').val() && +$('#pay_amount').val() > amountDue() + 0.004) {
        toastr.error(`Amount cannot be more than the amount due (${money(amountDue())}).`);
        return;
    }

    const id = $('#pay_payment_id').val();
    const url = id ? `/payments/${id}` : '{{ route('payments.store') }}';
    const method = id ? 'PUT' : 'POST';

    $.ajax({
        url, method, data: $('#payment-form').serialize(),
        success: function (res) {
            $('#paymentModal').modal('hide');
            toastr.success(res.message);
            paymentsTable.ajax.reload();
            if (res.receipt_url) window.open(res.receipt_url, '_blank');
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                toastr.error(Object.values(xhr.responseJSON.errors || {}).flat()[0] || xhr.responseJSON.message || 'Validation failed.');
            } else {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
            }
        }
    });
}

function deletePayment(id) {
    confirmDelete(`/payments/${id}`, () => paymentsTable.ajax.reload());
}
</script>
@endpush
