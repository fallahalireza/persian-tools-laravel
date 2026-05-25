<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;
use FallahAlireza\PersianTools\Data\PhoneAreaCodes;

/**
 * Validates Iranian landline phone numbers.
 *
 * Valid:   02112345678, 021-12345678, +9802112345678
 * Invalid: 0211234, 09123456789
 */
class IranianPhone extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected bool $withAreaCode = false,
        protected ?string $areaCodeSeparator = null,
        protected ?string $withCountryCodeFormat = null,
        protected bool $convertPersianNumbers = false,
    ) {}

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            withAreaCode: isset($parameters[0]) && filter_var($parameters[0], FILTER_VALIDATE_BOOLEAN),
            areaCodeSeparator: $parameters[1] ?? null,
            withCountryCodeFormat: $parameters[2] ?? null,
            convertPersianNumbers: isset($parameters[3]) && filter_var($parameters[3], FILTER_VALIDATE_BOOLEAN),
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

        if ($this->withCountryCodeFormat) {
            return $this->validateWithCountryCode($value);
        }

        if ($this->withAreaCode) {
            return $this->validateWithAreaCode($value);
        }

        return (bool) preg_match('/^\d{8}$/', $value);
    }

    protected function validateWithAreaCode(string $value): bool
    {
        $sep = $this->areaCodeSeparator ? preg_quote($this->areaCodeSeparator, '/') : '';
        $pattern = $sep
            ? "/^0(\d{2,3}){$sep}(\d{8})$/"
            : '/^0(\d{2,3})(\d{8})$/';

        if (! preg_match($pattern, $value, $matches)) {
            return false;
        }

        return in_array('0'.$matches[1], PhoneAreaCodes::allCodes(), true);
    }

    protected function validateWithCountryCode(string $value): bool
    {
        $prefixPattern = match ($this->withCountryCodeFormat) {
            'zero' => '0098',
            'plus' => '\+98',
            'normal' => '98',
            default => '(?:0098|\+98|98)',
        };

        $pattern = "/^{$prefixPattern}(\d{2,3})(\d{8})$/";

        if (! preg_match($pattern, $value, $matches)) {
            return false;
        }

        return in_array('0'.$matches[1], PhoneAreaCodes::allCodes(), true);
    }

    protected function translationKey(): string
    {
        return 'ir_phone';
    }
}
