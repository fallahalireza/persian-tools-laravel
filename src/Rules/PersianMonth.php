<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Data\PersianCalendar;

/**
 * Validates Persian month names.
 *
 * Valid:   فروردین، اردیبهشت، خرداد، ...
 * Invalid: January, March
 */
class PersianMonth extends BaseRule
{
    public function passes(string $attribute, mixed $value): bool
    {
        return in_array($value, PersianCalendar::months(), true);
    }

    protected function translationKey(): string
    {
        return 'persian_month';
    }
}
