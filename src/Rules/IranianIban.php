<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;

/**
 * Validates Iranian IBAN (شماره شبا) - 26 chars starting with IR.
 *
 * Valid:   IR062960000000100324200001
 * Invalid: IR062960000000
 */
class IranianIban extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected bool $withPrefix = true,
        protected ?string $separator = null,
        protected bool $convertPersianNumbers = false,
    ) {
    }

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            withPrefix: ! isset($parameters[0]) || filter_var($parameters[0], FILTER_VALIDATE_BOOLEAN),
            separator: $parameters[1] ?? null,
            convertPersianNumbers: isset($parameters[2]) && filter_var($parameters[2], FILTER_VALIDATE_BOOLEAN),
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

        // Remove separator
        if ($this->separator) {
            $sep = preg_quote($this->separator, '/');
            $value = preg_replace("/{$sep}/", '', $value) ?? '';
        }

        $value = strtoupper($value);

        if ($this->withPrefix) {
            if (! preg_match('/^IR\d{24}$/', $value)) {
                return false;
            }
        } else {
            if (! preg_match('/^\d{24}$/', $value)) {
                return false;
            }
            $value = 'IR'.$value;
        }

        return $this->verifyIban($value);
    }

    protected function verifyIban(string $iban): bool
    {
        // Move first 4 chars to end
        $rearranged = substr($iban, 4).substr($iban, 0, 4);

        // Replace letters with numbers (A=10, B=11, ...)
        $numeric = '';
        foreach (str_split($rearranged) as $char) {
            $numeric .= ctype_alpha($char) ? (ord($char) - 55) : $char;
        }

        // Mod 97 using chunk method (handles large numbers)
        $remainder = 0;
        foreach (str_split($numeric, 7) as $chunk) {
            $remainder = (int) (((string) $remainder).$chunk) % 97;
        }

        return $remainder === 1;
    }

    protected function translationKey(): string
    {
        return 'ir_iban';
    }
}
