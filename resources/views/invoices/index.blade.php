@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('breadcrumb')
    <li class="breadcrumb-item active">Invoices</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Invoices</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateInvoice()"><i class="fas fa-plus"></i> New Invoice</button>
    </div>
    <div class="card-body">
        <table id="invoices-table" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Invoice #</th><th>Patient</th><th>Issue Date</th>
                    <th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="invoice-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">New Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="invoice_id" id="invoice_id">

                    <div class="mb-3">
                        <label class="form-label">Patient *</label>
                        <div class="patient-search-box">
                            <input type="text" id="inv_patient_search" class="form-control" placeholder="Search name, ID, mobile..." autocomplete="off" required>
                            <input type="hidden" name="patient_id" id="inv_patient_id">
                            <div class="search-results" id="inv_patient_results"></div>
                        </div>
                        <small class="text-muted" id="inv_patient_meta"></small>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Issue Date *</label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                    </div>

                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Items *</span>
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="addItemRow()"><i class="fas fa-plus"></i> Add Item</button>
                    </label>
                    <div id="items-body"></div>

                    <div class="invoice-totals mt-3">
                        <div><span>Subtotal</span><b id="calc-subtotal">$0.00</b></div>
                        <div><span>Discount</span><b id="calc-discount">$0.00</b></div>
                        <div class="grand"><span>Grand Total</span><b id="calc-total">$0.00</b></div>
                    </div>

                    <div class="mb-1 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="item-row-template">
    <div class="item-row">
        <div class="item-row-main">
            <select class="form-control form-control-sm item-service">
                <option value="">Custom item...</option>
                @foreach ($services as $s)
                    <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-name="{{ $s->name }}">{{ $s->name }} — ${{ number_format($s->price,2) }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger btn-xs item-remove" onclick="removeItemRow(this)"><i class="fas fa-times"></i></button>
        </div>
        <input type="text" class="form-control form-control-sm item-description mt-1" placeholder="Description">
        <div class="item-row-fields">
            <div>
                <label>Tooth #</label>
                <input type="text" class="form-control form-control-sm item-tooth">
            </div>
            <div>
                <label>Qty</label>
                <input type="number" class="form-control form-control-sm item-qty" value="1" min="1">
            </div>
            <div>
                <label>Unit Price</label>
                <input type="number" class="form-control form-control-sm item-price" value="0" min="0" step="0.01">
            </div>
            <div>
                <label>Discount</label>
                <input type="number" class="form-control form-control-sm item-discount" value="0" min="0" step="0.01">
            </div>
        </div>
        <div class="item-row-total">Line total: <b class="item-line-total">$0.00</b></div>
    </div>
</template>
@endsection

@push('css')
<style>
.item-row { background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: .7rem .8rem; margin-bottom: .6rem; }
.item-row-main { display: flex; gap: .5rem; align-items: center; }
.item-row-main select { flex: 1; }
.item-row-fields { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; margin-top: .5rem; }
.item-row-fields label { font-size: .68rem; color: var(--text-soft); margin-bottom: .15rem; display: block; }
.item-row-total { text-align: right; font-size: .8rem; color: var(--text-muted); margin-top: .4rem; }
.invoice-totals { background: var(--surface-2); border-radius: var(--radius-sm); padding: .8rem 1rem; }
.invoice-totals > div { display: flex; justify-content: space-between; font-size: .85rem; padding: .25rem 0; color: var(--text-muted); }
.invoice-totals .grand { border-top: 1px solid var(--border); margin-top: .3rem; padding-top: .5rem; font-size: 1rem; color: var(--text); }
</style>
@endpush

@push('js')
<script>
let invoicesTable;

$(function () {
    invoicesTable = $('#invoices-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: '{{ route('invoices.index') }}', type: 'GET' },
        columns: [
            { data: 'invoice_no' },
            { data: 'patient' },
            { data: 'issue_date' },
            { data: 'total_amount' },
            { data: 'paid_amount' },
            { data: 'balance' },
            { data: null, render: r => `<span class="badge bg-${r.status_color}">${r.status.replace('_',' ')}</span>` },
            {
                data: null, orderable: false, searchable: false,
                render: (row) => `
                    <a href="/invoices/${row.id}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                    <a href="/invoices/${row.id}/pdf" target="_blank" class="btn btn-secondary btn-xs"><i class="fas fa-file-pdf"></i></a>
                    ${row.editable ? `<button class="btn btn-warning btn-xs" onclick='openEditInvoice(${row.id})'><i class="fas fa-edit"></i></button>` : ''}
                    <button class="btn btn-danger btn-xs" onclick="deleteInvoice(${row.id})"><i class="fas fa-trash"></i></button>
                `
            }
        ]
    });

    $('#items-body').on('input', '.item-qty, .item-price, .item-discount', calculateTotals);
    $('#items-body').on('change', '.item-service', function () {
        const row = $(this).closest('.item-row');
        const opt = $(this).find(':selected');
        if (opt.val()) {
            row.find('.item-description').val(opt.data('name'));
            row.find('.item-price').val(opt.data('price'));
        }
        calculateTotals();
    });

    initPatientSearch('#inv_patient_search', '#inv_patient_results', function (patient) {
        $('#inv_patient_id').val(patient.id);
        $('#inv_patient_meta').text(patient.meta);
    });
});

function addItemRow(data = null) {
    const row = $(document.importNode(document.getElementById('item-row-template').content, true));
    if (data) {
        row.find('.item-service').val(data.service_id || '');
        row.find('.item-description').val(data.description);
        row.find('.item-tooth').val(data.tooth_number);
        row.find('.item-qty').val(data.quantity);
        row.find('.item-price').val(data.unit_price);
        row.find('.item-discount').val(data.discount_amount);
    }
    $('#items-body').append(row);
    calculateTotals();
}

function removeItemRow(btn) {
    $(btn).closest('.item-row').remove();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0, discount = 0;
    $('#items-body .item-row').each(function () {
        const qty = parseFloat($(this).find('.item-qty').val()) || 0;
        const price = parseFloat($(this).find('.item-price').val()) || 0;
        const disc = parseFloat($(this).find('.item-discount').val()) || 0;
        const lineTotal = (qty * price) - disc;
        $(this).find('.item-line-total').text('$' + lineTotal.toFixed(2));
        subtotal += qty * price;
        discount += disc;
    });
    $('#calc-subtotal').text('$' + subtotal.toFixed(2));
    $('#calc-discount').text('$' + discount.toFixed(2));
    $('#calc-total').text('$' + (subtotal - discount).toFixed(2));
}

function openCreateInvoice() {
    $('#invoice-form')[0].reset();
    $('#invoice_id').val('');
    $('#inv_patient_id').val('');
    $('#inv_patient_meta').text('');
    $('#items-body').empty();
    $('#invoiceModalLabel').text('New Invoice');
    addItemRow();
    calculateTotals();
    $('#invoiceModal').modal('show');
}

function openEditInvoice(id) {
    $.get(`/invoices/${id}/edit`, function (invoice) {
        $('#invoice-form')[0].reset();
        $('#invoice_id').val(invoice.id);
        $('#inv_patient_id').val(invoice.patient_id);
        $('#inv_patient_search').val(invoice.patient ? (invoice.patient.first_name + ' ' + invoice.patient.last_name) : '');
        $('#invoice-form [name="issue_date"]').val(invoice.issue_date);
        $('#invoice-form [name="due_date"]').val(invoice.due_date);
        $('#invoice-form [name="notes"]').val(invoice.notes);
        $('#items-body').empty();
        invoice.items.forEach(item => addItemRow(item));
        calculateTotals();
        $('#invoiceModalLabel').text('Edit Invoice');
        $('#invoiceModal').modal('show');
    });
}

$('#invoice-form').on('submit', function (e) {
    e.preventDefault();
    if (!$('#inv_patient_id').val()) {
        toastr.error('Please select a patient from the search results.');
        return;
    }
    if (!$('#items-body .item-row').length) {
        toastr.error('Please add at least one item.');
        return;
    }

    const id = $('#invoice_id').val();
    const url = id ? `/invoices/${id}` : '{{ route('invoices.store') }}';
    const method = id ? 'PUT' : 'POST';

    const items = [];
    $('#items-body .item-row').each(function () {
        items.push({
            service_id: $(this).find('.item-service').val() || null,
            description: $(this).find('.item-description').val(),
            tooth_number: $(this).find('.item-tooth').val(),
            quantity: $(this).find('.item-qty').val(),
            unit_price: $(this).find('.item-price').val(),
            discount_amount: $(this).find('.item-discount').val(),
            tax_amount: 0,
        });
    });

    const formData = $(this).serializeArray().reduce((acc, x) => { acc[x.name] = x.value; return acc; }, {});
    formData.items = items;

    $.ajax({
        url, method, data: formData,
        success: function (res) {
            $('#invoiceModal').modal('hide');
            toastr.success(res.message);
            invoicesTable.ajax.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                toastr.error(Object.values(xhr.responseJSON.errors).flat()[0] || 'Please check the form for errors.');
            } else {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
            }
        }
    });
});

function deleteInvoice(id) {
    confirmDelete(`/invoices/${id}`, () => invoicesTable.ajax.reload());
}
</script>
@endpush
