<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;
use FallahAlireza\PersianTools\Detectors\BankDetector;

/**
 * Validates Iranian bank card numbers (16 digits) with Luhn algorithm.
 * Also identifies the issuing bank via BIN prefix.
 *
 * Valid:   6037991234567890, 6037-9912-3456-7890
 * Invalid: 6037991234567, 603799123456789a
 */
class IranianBankCardNumber extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected ?string $separator = null,
        protected bool $convertPersianNumbers = false,
    ) {}

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

        if ($this->separator) {
            $sep = preg_quote($this->separator, '/');
            $value = preg_replace("/{$sep}/", '', $value) ?? '';
        }

        if (! preg_match('/^\d{16}$/', $value)) {
            return false;
        }

        return $this->luhn($value);
    }

    /**
     * Detect the bank name from a card number.
     *
     * @deprecated  Use BankDetector::fromCardNumber() directly for non-validation use cases.
     */
    public function detectBank(string $cardNumber): ?string
    {
        return BankDetector::fromCardNumber($cardNumber);
    }

    protected function luhn(string $number): bool
    {
        $sum = 0;
        $isOdd = true;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];

            if ($isOdd) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $isOdd = ! $isOdd;
        }

        return $sum % 10 === 0;
    }

    protected function translationKey(): string
    {
        return 'ir_bank_card';
    }
}
