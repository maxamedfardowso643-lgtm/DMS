<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'photo',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function dentist(): HasOne
    {
        return $this->hasOne(Dentist::class);
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles->contains('slug', $slug);
    }

    public function hasAnyRole(array $slugs): bool
    {
        return $this->roles->pluck('slug')->intersect($slugs)->isNotEmpty();
    }

    /**
     * Dentist id to limit appointment lists to, or null when the user should see the whole clinic.
     */
    public function scopedDentistId(): ?int
    {
        if (! $this->hasRole(Role::DENTIST) || $this->hasAnyRole([Role::ADMIN, Role::RECEPTIONIST])) {
            return null;
        }

        return $this->dentist?->id;
    }

    /**
     * Photos under images/ ship with the app (public/); anything else is an upload on the public disk.
     */
    public function photoUrl(int $size = 128): string
    {
        if (! $this->photo) {
            return 'https://ui-avatars.com/api/?background=4f46e5&color=fff&size=' . $size . '&name=' . urlencode($this->name);
        }

        return str_starts_with($this->photo, 'images/') ? asset($this->photo) : asset('storage/' . $this->photo);
    }
}
