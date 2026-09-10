@extends('layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory')
@section('breadcrumb')
    <li class="breadcrumb-item active">Inventory</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Dental Supplies</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateItem()"><i class="fas fa-plus"></i> Add Item</button>
    </div>
    <div class="card-body">
        <table id="inventory-table" class="table table-bordered table-striped w-100">
            <thead><tr><th>Code</th><th>Name</th><th>Unit</th><th>Qty on Hand</th><th>Reorder Level</th><th>Unit Cost</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="item-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="item_id">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" placeholder="box, pcs, ml"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Unit Cost *</label><input type="number" step="0.01" name="unit_cost" class="form-control" required></div>
                        <div class="col-md-6 mb-3" id="qty-field"><label class="form-label">Quantity on Hand *</label><input type="number" name="quantity_on_hand" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Reorder Level *</label><input type="number" name="reorder_level" class="form-control" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Supplier</label><input type="text" name="supplier" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="movementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="movement-form">
                <div class="modal-header">
                    <h5 class="modal-title">Stock In / Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="movement_item_id">
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select id="movement_type" class="form-control">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Quantity</label><input type="number" id="movement_quantity" class="form-control" min="1" required></div>
                    <div class="mb-3"><label class="form-label">Reason</label><input type="text" id="movement_reason" class="form-control" placeholder="purchase, usage, adjustment..."></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let inventoryTable;

$(function () {
    inventoryTable = $('#inventory-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route('inventory.index') }}', type: 'GET' },
        columns: [
            { data: 'item_code' }, { data: 'name' }, { data: 'unit', defaultContent: '-' },
            { data: null, render: r => r.low_stock ? `<span class="text-danger fw-bold">${r.quantity_on_hand}</span>` : r.quantity_on_hand },
            { data: 'reorder_level' }, { data: 'unit_cost' },
            { data: 'id', orderable: false, searchable: false, render: id => `
                <button class="btn btn-success btn-xs" onclick='openMovementModal(${id})'><i class="fas fa-exchange-alt"></i></button>
                <button class="btn btn-warning btn-xs" onclick='openEditItem(${id})'><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-xs" onclick="deleteItem(${id})"><i class="fas fa-trash"></i></button>
            `}
        ]
    });
});

function openCreateItem() {
    $('#item-form')[0].reset();
    $('#item_id').val('');
    $('#qty-field').show();
    $('#itemModalLabel').text('Add Item');
    $('#itemModal').modal('show');
}

function openEditItem(id) {
    $.get(`/inventory/${id}/edit`, function (i) {
        $('#item-form')[0].reset();
        $('#item_id').val(i.id);
        $.each(i, (k, v) => $(`#item-form [name="${k}"]`).val(v));
        $('#qty-field').hide();
        $('#itemModalLabel').text('Edit Item');
        $('#itemModal').modal('show');
    });
}

$('#item-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#item_id').val();
    const url = id ? `/inventory/${id}` : '{{ route('inventory.store') }}';
    const method = id ? 'PUT' : 'POST';
    $.ajax({
        url, method, data: $(this).serialize(),
        success: function (res) {
            $('#itemModal').modal('hide');
            toastr.success(res.message);
            inventoryTable.ajax.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) showFormErrors($('#item-form'), xhr.responseJSON.errors);
            else toastr.error('Something went wrong.');
        }
    });
});

function openMovementModal(id) {
    $('#movement-form')[0].reset();
    $('#movement_item_id').val(id);
    $('#movementModal').modal('show');
}

$('#movement-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#movement_item_id').val();
    $.post(`/inventory/${id}/stock-movement`, {
        type: $('#movement_type').val(),
        quantity: $('#movement_quantity').val(),
        reason: $('#movement_reason').val(),
    }).done(res => {
        $('#movementModal').modal('hide');
        toastr.success(res.message);
        inventoryTable.ajax.reload();
    }).fail(() => toastr.error('Something went wrong.'));
});

function deleteItem(id) {
    confirmDelete(`/inventory/${id}`, () => inventoryTable.ajax.reload());
}
</script>
@endpush
