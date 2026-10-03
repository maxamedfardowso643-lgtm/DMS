<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = ['draft', 'unpaid', 'partially_paid', 'paid', 'overdue', 'cancelled'];

    const STATUS_COLORS = [
        'draft' => 'secondary',
        'unpaid' => 'warning',
        'partially_paid' => 'info',
        'paid' => 'success',
        'overdue' => 'danger',
        'cancelled' => 'dark',
    ];

    protected $fillable = [
        'invoice_no', 'appointment_id', 'patient_id', 'issue_date', 'due_date',
        'subtotal', 'discount_amount', 'tax_amount', 'total_amount', 'paid_amount',
        'status', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public static function nextNumber(): string
    {
        $year = date('Y');
        $last = self::where('invoice_no', 'like', "INV-$year-%")
            ->withTrashed()->orderByDesc('invoice_no')->value('invoice_no');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "INV-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->total_amount - (float) $this->paid_amount;
    }

    public function statusBadgeColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'unpaid']);
    }
}
