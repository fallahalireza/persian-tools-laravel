<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Iranian National ID (کد ملی) with checksum algorithm.
 *
 * Valid:   0013542419
 * Invalid: 0000000000, 1234567890 (bad checksum)
 */
class IranianNationalId extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected bool $convertPersianNumbers = false,
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

        if (! preg_match('/^\d{10}$/', $value)) {
            return false;
        }

        // Reject all-same-digit values
        if (preg_match('/^(\d)\1{9}$/', $value)) {
            return false;
        }

        return $this->verifyChecksum($value);
    }

    protected function verifyChecksum(string $id): bool
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $id[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $checkDigit = (int) $id[9];

        return $remainder < 2
            ? $checkDigit === $remainder
            : $checkDigit === (11 - $remainder);
    }

    protected function translationKey(): string
    {
        return 'ir_national_id';
    }
}
