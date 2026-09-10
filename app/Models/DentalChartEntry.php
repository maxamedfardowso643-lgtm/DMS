<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentalChartEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'treatment_id', 'tooth_number', 'condition', 'notes', 'recorded_date',
    ];

    protected function casts(): array
    {
        return ['recorded_date' => 'date'];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }
}
