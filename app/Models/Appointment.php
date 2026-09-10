<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'booked', 'confirmed', 'checked_in', 'in_progress',
        'completed', 'cancelled', 'no_show',
    ];

    const STATUS_COLORS = [
        'booked' => 'secondary',
        'confirmed' => 'info',
        'checked_in' => 'primary',
        'in_progress' => 'warning',
        'completed' => 'success',
        'cancelled' => 'danger',
        'no_show' => 'dark',
    ];

    protected $fillable = [
        'appointment_no', 'patient_id', 'dentist_id', 'service_id',
        'appointment_date', 'start_time', 'end_time', 'source', 'status',
        'notes', 'cancellation_reason', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(AppointmentStatusLog::class)->latest();
    }

    public function treatment(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function statusBadgeColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }
}
