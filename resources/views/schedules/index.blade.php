@extends('layouts.app')

@section('title', 'Schedules')
@section('page-title', 'Dentist Schedules &amp; Leave')
@section('breadcrumb')
    <li class="breadcrumb-item active">Schedules</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Working Hours &amp; Leave</h3>
        <button class="btn btn-primary btn-sm" onclick="openScheduleModal()"><i class="fas fa-plus"></i> Add Schedule / Leave</button>
    </div>
    <div class="card-body">
        @php $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; @endphp
        @foreach ($dentists as $dentist)
            <h5>{{ $dentist->user->name }} <small class="text-muted">{{ $dentist->specialization }}</small></h5>
            <table class="table table-sm table-bordered mb-4">
                <thead><tr><th>Type</th><th>Day / Date</th><th>Time</th><th>Reason</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse ($dentist->schedules as $s)
                        <tr>
                            <td class="text-capitalize">{{ $s->type }}</td>
                            <td>{{ $s->type === 'weekly' ? $days[$s->day_of_week] : $s->leave_date?->format('Y-m-d') }}</td>
                            <td>{{ $s->start_time ? substr($s->start_time,0,5).' - '.substr($s->end_time,0,5) : '-' }}</td>
                            <td>{{ $s->reason ?? '-' }}</td>
                            <td><button class="btn btn-danger btn-xs" onclick="deleteSchedule({{ $s->id }})"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No schedule entries.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach
    </div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="schedule-form">
                <div class="modal-header">
                    <h5 class="modal-title">Add Schedule / Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Dentist *</label>
                        <select name="dentist_id" class="form-control" required>
                            @foreach ($dentists as $d)
                                <option value="{{ $d->id }}">{{ $d->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select name="type" id="schedule_type" class="form-control" required>
                            <option value="weekly">Weekly Working Hours</option>
                            <option value="leave">Leave / Holiday</option>
                        </select>
                    </div>
                    <div id="weekly-fields">
                        <div class="mb-3">
                            <label class="form-label">Day of Week</label>
                            <select name="day_of_week" class="form-control">
                                <option value="0">Sunday</option><option value="1">Monday</option><option value="2">Tuesday</option>
                                <option value="3">Wednesday</option><option value="4">Thursday</option><option value="5">Friday</option><option value="6">Saturday</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label">Start</label><input type="time" name="start_time" class="form-control" value="09:00"></div>
                            <div class="col-6 mb-3"><label class="form-label">End</label><input type="time" name="end_time" class="form-control" value="17:00"></div>
                        </div>
                    </div>
                    <div id="leave-fields" style="display:none;">
                        <div class="mb-3"><label class="form-label">Leave Date</label><input type="date" name="leave_date" class="form-control"></div>
                        <div class="mb-3"><label class="form-label">Reason</label><input type="text" name="reason" class="form-control"></div>
                    </div>
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
function openScheduleModal() {
    $('#schedule-form')[0].reset();
    $('#scheduleModal').modal('show');
}

$('#schedule_type').on('change', function () {
    if ($(this).val() === 'weekly') {
        $('#weekly-fields').show(); $('#leave-fields').hide();
    } else {
        $('#weekly-fields').hide(); $('#leave-fields').show();
    }
});

$('#schedule-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route('schedules.store') }}', method: 'POST', data: $(this).serialize(),
        success: function (res) {
            toastr.success(res.message);
            location.reload();
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
        }
    });
});

function deleteSchedule(id) {
    confirmDelete(`/schedules/${id}`, () => location.reload());
}
</script>
@endpush
