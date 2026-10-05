<?php

namespace App\Models;

use App\Support\Uploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * The clinic logo path, or null when the image file is gone, so pages
     * fall back to the default icon instead of showing a broken image.
     */
    public static function logo(): ?string
    {
        static $checked = [];

        $logo = static::get('clinic_logo');

        if (! $logo) {
            return null;
        }

        return ($checked[$logo] ??= Uploads::ensureOnDisk($logo)) ? $logo : null;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.$key");
    }
}
