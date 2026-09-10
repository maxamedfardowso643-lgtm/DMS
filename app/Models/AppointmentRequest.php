<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'service_id', 'preferred_date', 'preferred_time', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return ['preferred_date' => 'date'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
