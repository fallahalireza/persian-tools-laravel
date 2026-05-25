<?php

namespace FallahAlireza\PersianTools\Rules;

/**
 * Validates only Persian digits (۰-۹).
 *
 * Valid:   ۱۲۳۴۵۶۷۸۹۰
 * Invalid: 1234567890, abc
 */
class PersianNum extends BaseRule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        return (bool) preg_match('/^[۰-۹]+$/u', $value);
    }

    protected function translationKey(): string
    {
        return 'persian_num';
    }
}
