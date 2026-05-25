<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;
use FallahAlireza\PersianTools\Data\MobileOperators;
use FallahAlireza\PersianTools\Enums\MobileFormat;

/**
 * Validates Iranian mobile numbers.
 *
 * Valid:   09123456789, +989123456789, 989123456789
 * Invalid: 091234567, 08123456789
 */
class IranianMobile extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected MobileFormat $format = MobileFormat::All,
        protected bool $convertPersianNumbers = false,
    ) {
    }

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            format: MobileFormat::tryFrom($parameters[0] ?? 'all') ?? MobileFormat::All,
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

        $pattern = match ($this->format) {
            MobileFormat::ZeroCode => '/^0098(9\d{9})$/',
            MobileFormat::PlusCode => '/^\+98(9\d{9})$/',
            MobileFormat::Code => '/^98(9\d{9})$/',
            MobileFormat::Zero => '/^0(9\d{9})$/',
            MobileFormat::Normal => '/^(9\d{9})$/',
            MobileFormat::All => '/^(0098|\+98|98|0)?(9\d{9})$/',
        };

        if (! preg_match($pattern, $value, $matches)) {
            return false;
        }

        $mobile = end($matches);
        $prefix = substr($mobile, 0, 3);

        return in_array($prefix, MobileOperators::allPrefixes(), true);
    }

    protected function translationKey(): string
    {
        return 'ir_mobile';
    }
}
