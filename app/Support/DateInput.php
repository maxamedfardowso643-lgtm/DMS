<?php

namespace App\Support;

class DateInput
{
    /**
     * Converts a typed DD/MM/YYYY (also D/M/YYYY with / . or - separators) date to Y-m-d.
     * Anything else is returned unchanged so the regular "date" rule can judge it.
     */
    public static function toIso(mixed $value): mixed
    {
        if (is_string($value) && preg_match('/^\s*(\d{1,2})[\/.\-](\d{1,2})[\/.\-](\d{4})\s*$/', $value, $m)
            && checkdate((int) $m[2], (int) $m[1], (int) $m[3])) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }

        return $value;
    }
}
