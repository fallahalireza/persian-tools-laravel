<?php

namespace FallahAlireza\PersianTools\Concerns;

trait NormalizesPersianNumbers
{
    /**
     * Convert Persian/Arabic digits to English digits.
     */
    protected function normalizePersianNumbers(string $value): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace([...$persian, ...$arabic], [...$english, ...$english], $value);
    }

    /**
     * Check if string contains Persian/Arabic digits.
     */
    protected function hasPersianDigits(string $value): bool
    {
        return (bool) preg_match('/[۰-۹٠-٩]/', $value);
    }
}
