<?php

namespace FallahAlireza\PersianTools\Rules;

/**
 * Rejects any Persian/Arabic characters or digits.
 *
 * Valid:   Hello 123, Test
 * Invalid: سلام، تست۱۲۳
 */
class PersianNotAccept extends BaseRule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        return ! preg_match('/[\x{0600}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $value);
    }

    protected function translationKey(): string
    {
        return 'persian_not_accept';
    }
}
