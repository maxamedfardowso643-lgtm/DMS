<?php

namespace App\Support\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Resolves the shared set of date-range presets used across the reporting
 * center (Today, This Month, Last Quarter, Custom Range, ...) into a
 * concrete [from, to] pair, so every report reads filters the same way.
 */
class DateRangeResolver
{
    public const PRESETS = [
        'yesterday' => 'Yesterday',
        'last_month' => 'Last Month',
        'this_quarter' => 'This Quarter',
        'custom' => 'Custom Range',
    ];

    /**
     * @return array{preset:string, from:string, to:string, label:string}
     */
    public static function resolve(Request $request, string $default = 'custom'): array
    {
        $preset = $request->get('preset', $default);

        if (! array_key_exists($preset, self::PRESETS)) {
            $preset = $default;
        }

        if ($preset === 'custom') {
            $from = $request->get('from') ?: now()->startOfMonth()->format('Y-m-d');
            $to = $request->get('to') ?: now()->format('Y-m-d');

            return ['preset' => 'custom', 'from' => $from, 'to' => $to, 'label' => 'Custom Range'];
        }

        [$from, $to] = match ($preset) {
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'this_quarter' => [now()->startOfQuarter(), now()->endOfQuarter()],
            default => [now()->startOfMonth(), now()],
        };

        return [
            'preset' => $preset,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'label' => self::PRESETS[$preset],
        ];
    }

    public static function label(string $preset): string
    {
        return self::PRESETS[$preset] ?? 'Custom Range';
    }

    public static function formatRange(string $from, string $to): string
    {
        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        if ($fromDate->isSameDay($toDate)) {
            return $fromDate->format('M j, Y');
        }

        return $fromDate->format('M j, Y').' – '.$toDate->format('M j, Y');
    }
}
