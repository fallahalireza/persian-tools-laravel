<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Iranian Postal Codes (کد پستی) - 10 digits.
 *
 * Valid:   1619735744, 16197-35744, ۱۶۱۹۷-۳۵۷۴۴
 * Invalid: 123456789, 12345678901
 */
class IranianPostalCode extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected ?string $separator = null,
        protected bool $convertPersianNumbers = false,
    ) {
    }

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            separator: $parameters[0] ?? null,
            convertPersianNumbers: isset($parameters[1]) && filter_var($parameters[1], FILTER_VALIDATE_BOOLEAN),
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

        // Must not start with 0
        if ($this->separator) {
            $sep = preg_quote($this->separator, '/');
            $pattern = "/^[1-9]\d{4}{$sep}\d{5}$/";
        } else {
            $pattern = '/^[1-9]\d{9}$/';
        }

        return (bool) preg_match($pattern, $value);
    }

    protected function translationKey(): string
    {
        return 'ir_postal_code';
    }
}
