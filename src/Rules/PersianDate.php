<?php

namespace FallahAlireza\PersianTools\Rules;

use FallahAlireza\PersianTools\Concerns\NormalizesPersianNumbers;
use FallahAlireza\PersianTools\Data\PersianCalendar;

/**
 * Validates a Shamsi (Jalali) date.
 *
 * Valid:   1403/01/01, ۱۴۰۳-۰۱-۰۱, 1403.6.31
 * Invalid: 2024/03/20, 1403/13/01, 1403/01/32
 */
class PersianDate extends BaseRule
{
    use NormalizesPersianNumbers;

    public function __construct(
        protected string $separator = '/',
        protected bool $convertPersianNumbers = false,
    ) {}

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            separator: $parameters[0] ?? '/',
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

        return $this->isValidDate($value);
    }

    protected function isValidDate(string $value): bool
    {
        $sep = preg_quote($this->separator, '/');
        $pattern = "/^(\d{4}){$sep}(\d{1,2}){$sep}(\d{1,2})$/";

        if (! preg_match($pattern, $value, $matches)) {
            return false;
        }

        [, $year, $month, $day] = array_map('intval', $matches);

        if ($year < 1000 || $year > 1600) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        $monthMaxDays = PersianCalendar::monthMaxDays();
        $maxDay = $monthMaxDays[$month];

        if ($month === 12 && $this->isLeapYear($year)) {
            $maxDay = 30;
        }

        return $day >= 1 && $day <= $maxDay;
    }

    protected function isLeapYear(int $year): bool
    {
        $remainder = ((($year - 474) % 2820) + 474 + 38) * 682 % 2816;

        return $remainder < 682;
    }

    protected function translationKey(): string
    {
        return 'persian_date';
    }
}
