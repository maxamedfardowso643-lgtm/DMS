@extends('layouts.app')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods')
@section('breadcrumb')
    <li class="breadcrumb-item active">Payment Methods</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Accepted Payment Methods &amp; Accounts</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateMethod()"><i class="fas fa-plus"></i> Add Method</button>
    </div>
    <div class="card-body">
        <div class="row" id="methods-grid">
            @foreach ($methods as $m)
                <div class="col-md-4 mb-3">
                    <div class="p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border);">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">{{ $m->name }}</div>
                                <div class="text-muted small">{{ $m->account_number ?? 'No account number' }}</div>
                                @if ($m->account_name)
                                    <div class="text-muted small">{{ $m->account_name }}</div>
                                @endif
                            </div>
                            <span class="badge bg-{{ $m->is_active ? 'success' : 'secondary' }}">{{ $m->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-warning btn-xs" onclick='openEditMethod({{ $m->id }})'><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-xs" onclick="deleteMethod({{ $m->id }})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="methodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="method-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="methodModalLabel">Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="method_id" id="method_id">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required placeholder="e.g. e-Dahab, Sahal, Cash"></div>
                    <div class="mb-3" id="code-field"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" required placeholder="e.g. edahab"></div>
                    <div class="mb-3"><label class="form-label">Account Number</label><input type="text" name="account_number" class="form-control" placeholder="Hospital account / mobile number"></div>
                    <div class="mb-3"><label class="form-label">Account Name</label><input type="text" name="account_name" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function openCreateMethod() {
    $('#method-form')[0].reset();
    $('#method_id').val('');
    $('#code-field').show();
    $('#methodModalLabel').text('Add Payment Method');
    $('#methodModal').modal('show');
}

function openEditMethod(id) {
    $.get(`/payment-methods/${id}/edit`, function (m) {
        $('#method-form')[0].reset();
        $('#method_id').val(m.id);
        $.each(m, (k, v) => $(`#method-form [name="${k}"]`).val(v));
        $('#code-field').hide();
        $('#methodModalLabel').text('Edit Payment Method');
        $('#methodModal').modal('show');
    });
}

$('#method-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#method_id').val();
    const url = id ? `/payment-methods/${id}` : '{{ route('payment-methods.store') }}';
    const method = id ? 'PUT' : 'POST';
    $.ajax({
        url, method, data: $(this).serialize(),
        success: function (res) { toastr.success(res.message); location.reload(); },
        error: function (xhr) {
            if (xhr.status === 422) showFormErrors($('#method-form'), xhr.responseJSON.errors);
            else toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
        }
    });
});

function deleteMethod(id) {
    confirmDelete(`/payment-methods/${id}`, () => location.reload());
}
</script>
@endpush
