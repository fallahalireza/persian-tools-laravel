<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Persian alphabetic characters + Persian numbers (optionally converts them).
 *
 * Valid:   سلام۱۲۳، علی‌رضا۴۵۶
 * Invalid: Hello 123, Test
 */
class PersianAlphaNum extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected bool $convertPersianNumbers = false
    ) {}

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            convertPersianNumbers: isset($parameters[0]) && filter_var($parameters[0], FILTER_VALIDATE_BOOLEAN),
        );
    }

    public function passes(string $attribute, mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        if ($this->convertPersianNumbers) {
            $value = $this->normalizePersianNumbers($value);
        }

        return (bool) preg_match('/^[\x{0600}-\x{06FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}\x{200C}\s]+$/u', $value);
    }

    protected function translationKey(): string
    {
        return 'persian_alpha_num';
    }
}
