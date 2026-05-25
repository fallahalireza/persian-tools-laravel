<?php

namespace FallahAlireza\PersianTools\Rules;

/**
 * Validates a Shamsi date is between two dates (exclusive).
 *
 * Usage: ['required', new PersianDateBetween('1400/01/01', '1403/12/29')]
 */
class PersianDateBetween extends PersianDate
{
    public function __construct(
        protected string $startDate,
        protected string $endDate,
        string $separator = '/',
        bool $convertPersianNumbers = false,
    ) {
        parent::__construct($separator, $convertPersianNumbers);
    }

    /** @param array<int, string> $parameters */
    public static function fromParameters(array $parameters): static
    {
        // @phpstan-ignore new.static
        return new static(
            startDate: $parameters[0] ?? '',
            endDate: $parameters[1] ?? '',
            separator: $parameters[2] ?? '/',
            convertPersianNumbers: isset($parameters[3]) && filter_var($parameters[3], FILTER_VALIDATE_BOOLEAN),
        );
    }

    public function passes(string $attribute, mixed $value): bool
    {
        if (! parent::passes($attribute, $value)) {
            return false;
        }

        if (! is_string($value)) {
            return false;
        }

        $dateValue = $value;

        if ($this->convertPersianNumbers) {
            $dateValue = $this->normalizePersianNumbers($dateValue);
        }

        $valueInt = $this->dateToInt($dateValue);
        $startInt = $this->dateToInt($this->startDate);
        $endInt   = $this->dateToInt($this->endDate);

        return $valueInt > $startInt && $valueInt < $endInt;
    }

    protected function dateToInt(string $date): int
    {
        $sep = preg_quote($this->separator, '/');
        preg_match("/^(\d{4}){$sep}(\d{1,2}){$sep}(\d{1,2})$/", $date, $m);

        return (int) sprintf('%04d%02d%02d', $m[1], $m[2], $m[3]);
    }

    protected function translationKey(): string
    {
        return 'persian_date_between';
    }
}
