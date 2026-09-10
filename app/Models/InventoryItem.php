<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_code', 'name', 'unit', 'quantity_on_hand', 'reorder_level',
        'unit_cost', 'supplier', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity_on_hand <= $this->reorder_level;
    }
}
