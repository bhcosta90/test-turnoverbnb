<?php

declare(strict_types = 1);

use Carbon\Carbon;

function formatNumber(?float $number, string $locale = 'USA'): ?string
{
    if ($number === null) {
        return null;
    }

    return Number::currency($numberrm);
}

function formatDate(Carbon $date, bool $time = true): string
{
    $response = $date->format('m/d/Y');

    if ($time) {
        $response .= ' ' . $date->format('H:i:s');
    }

    return $response;
}
