<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Iranian bank account numbers (شماره حساب بانکی).
 * Each bank has its own format; this validates the general numeric format.
 *
 * Supported formats:
 *   - Standard numeric account: 10-16 digits
 *   - With separator (e.g. Bank Melli: 0106-555-1001)
 *
 * Valid:   0106555100116, 1234567890
 * Invalid: 123abc, too short
 */
class IranianBankAccountNumber extends BaseRule
{
    use NormalizesPersianNumbers;

    protected const int MIN_LENGTH = 9;

    protected const int MAX_LENGTH = 16;

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

        // Strip allowed separators (-, /, space)
        if ($this->separator) {
            $sep = preg_quote($this->separator, '/');
            $value = preg_replace("/{$sep}/", '', $value) ?? '';
        } else {
            $value = preg_replace('/[-\/ ]/', '', $value) ?? '';
        }

        if (! preg_match('/^\d+$/', $value)) {
            return false;
        }

        $len = strlen($value);

        return $len >= self::MIN_LENGTH && $len <= self::MAX_LENGTH;
    }

    protected function translationKey(): string
    {
        return 'ir_bank_account';
    }
}
