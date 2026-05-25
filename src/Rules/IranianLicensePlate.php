<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;
use FallahAlireza\PersianTools\Data\LicensePlateLetters;

/**
 * Validates Iranian vehicle license plates (پلاک خودرو).
 *
 * Supports:
 *   - Standard format:   12الف34567
 *   - Motorcycle format: 123456789
 *
 * Valid:   12الف34567, ۱۲الف۳۴۵۶۷
 * Invalid: ABED1234, 123456
 */
class IranianLicensePlate extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected bool $allowMotorcycle = false,
        protected bool $convertPersianNumbers = false,
    ) {}

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            allowMotorcycle: isset($parameters[0]) && filter_var($parameters[0], FILTER_VALIDATE_BOOLEAN),
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

        $clean = preg_replace('/[\s\-]+/u', '', $value) ?? '';

        if ($this->allowMotorcycle && $this->validateMotorcycle($clean)) {
            return true;
        }

        return $this->validateStandard($clean);
    }

    /**
     * Standard car plate: 2 digits + Persian letter(s) + 5 digits
     * Example: 12الف34567
     */
    protected function validateStandard(string $plate): bool
    {
        if (! preg_match('/^(\d{2})([\x{0600}-\x{06FF}]+)(\d{5})$/u', $plate, $matches)) {
            return false;
        }

        return in_array($matches[2], LicensePlateLetters::all(), true);
    }

    /**
     * Motorcycle plate: 9 digits
     * Example: 123456789
     */
    protected function validateMotorcycle(string $plate): bool
    {
        return (bool) preg_match('/^\d{9}$/', $plate);
    }

    protected function translationKey(): string
    {
        return 'ir_license_plate';
    }
}
