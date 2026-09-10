<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'dentist_id', 'type', 'day_of_week', 'start_time', 'end_time',
        'leave_date', 'reason', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'leave_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }
}
