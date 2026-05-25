<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Iranian Company National ID (شناسه ملی اشخاص حقوقی) - 11 digits.
 *
 * Valid:   14007650912
 * Invalid: 1234567890, 123456789012
 */
class IranianCompanyId extends BaseRule
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

        if (! preg_match('/^\d{11}$/', $value)) {
            return false;
        }

        return $this->verifyChecksum($value);
    }

    protected function verifyChecksum(string $id): bool
    {
        $d = (int) $id[9] + 2;
        $weights = [$d + 29, 4, $d + 22, $d + 13, $d + 26, $d + 25, $d + 28, $d + 27, $d + 30, $d + 24];
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += $weights[$i] * (int) $id[$i];
        }

        $remainder = $sum % 11;
        $remainder = $remainder === 10 ? 0 : $remainder;

        return $remainder === (int) $id[10];
    }

    protected function translationKey(): string
    {
        return 'ir_company_id';
    }
}
