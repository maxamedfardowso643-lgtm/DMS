<?php

namespace App\Support;

class ServiceIcon
{
    protected const MAP = [
        'preventive' => 'fa-tooth',
        'restorative' => 'fa-tools',
        'surgical' => 'fa-user-md',
        'cosmetic' => 'fa-smile',
        'orthodontic' => 'fa-grip-lines',
        'diagnostic' => 'fa-x-ray',
        'endodontic' => 'fa-syringe',
        'periodontic' => 'fa-shield-alt',
        'pediatric' => 'fa-child',
    ];

    public static function for(?string $category): string
    {
        return self::MAP[strtolower((string) $category)] ?? 'fa-tooth';
    }
}
