<?php

namespace FallahAlireza\PersianTools\Rules;

/**
 * Validates Persian alphabetic characters including diacritics, spaces, and ZWNJ.
 *
 * Valid:   سلام، علی‌رضا، مرحبا
 * Invalid: Hello, Test123, سلام1
 */
class PersianAlpha extends BaseRule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        // Persian letters + diacritics + space + ZWNJ + Arabic punctuation
        return (bool) preg_match('/^[\x{0600}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}\x{200C}\s]+$/u', $value);
    }

    protected function translationKey(): string
    {
        return 'persian_alpha';
    }
}
