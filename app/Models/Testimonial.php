<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'rating', 'message', 'is_approved', 'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_read' => 'boolean',
            'rating' => 'integer',
        ];
    }
}
