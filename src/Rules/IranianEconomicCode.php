<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Iranian Economic Code (کد اقتصادی) - 14 digits.
 * This is used for tax/business identification.
 *
 * Valid:   14004800101010
 * Invalid: 1234567890, 1234567890123 (not 14 digits)
 */
class IranianEconomicCode extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected bool $convertPersianNumbers = false,
    ) {
    }

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

        // Must be exactly 14 digits
        if (! preg_match('/^\d{14}$/', $value)) {
            return false;
        }

        // Reject all-same-digit values
        if (preg_match('/^(\d)\1{13}$/', $value)) {
            return false;
        }

        return $this->verifyChecksum($value);
    }

    protected function verifyChecksum(string $code): bool
    {
        $weights = [2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4, 5, 6];
        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $code[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $checkDigit = (int) $code[13];

        if ($remainder === 0 || $remainder === 1) {
            return $checkDigit === $remainder;
        }

        return $checkDigit === (11 - $remainder);
    }

    protected function translationKey(): string
    {
        return 'ir_economic_code';
    }
}
