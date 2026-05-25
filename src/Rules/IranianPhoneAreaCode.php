<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;
use FallahAlireza\PersianTools\Data\PhoneAreaCodes;

/**
 * Validates Iranian state phone area codes.
 *
 * Valid:   021, 031, ۰۲۱
 * Invalid: 099, 1234
 */
class IranianPhoneAreaCode extends BaseRule
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

        return in_array($value, PhoneAreaCodes::allCodes(), true);
    }

    protected function translationKey(): string
    {
        return 'ir_phone_area_code';
    }
}
