<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Data\PersianCalendar;

/**
 * Validates Persian day names.
 *
 * Valid:   شنبه، یکشنبه، دوشنبه، ...
 * Invalid: Saturday, Sunday
 */
class PersianDay extends BaseRule
{
    public function passes(string $attribute, mixed $value): bool
    {
        return in_array($value, PersianCalendar::days(), true);
    }

    protected function translationKey(): string
    {
        return 'persian_day';
    }
}
