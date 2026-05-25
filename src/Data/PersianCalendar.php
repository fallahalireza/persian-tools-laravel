<?php

namespace FallahAlireza\PersianTools\Data;

/**
 * Persian calendar data: month names, day names, and days-per-month.
 */
final class PersianCalendar
{
    /**
     * @return string[]
     */
    public static function months(): array
    {
        return [
            'فروردین', 'اردیبهشت', 'خرداد', 'تیر',
            'مرداد', 'شهریور', 'مهر', 'آبان',
            'آذر', 'دی', 'بهمن', 'اسفند',
        ];
    }

    /**
     * @return string[]
     */
    public static function days(): array
    {
        return [
            'شنبه', 'یکشنبه', 'دوشنبه',
            'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه',
        ];
    }

    /**
     * Maximum days per month (non-leap year).
     *
     * @return array<int, int> month number (1-12) => max days
     */
    public static function monthMaxDays(): array
    {
        return [
            1 => 31, 2 => 31, 3 => 31,
            4 => 31, 5 => 31, 6 => 31,
            7 => 30, 8 => 30, 9 => 30,
            10 => 30, 11 => 30, 12 => 29,
        ];
    }

    /**
     * Allowed date separators.
     *
     * @return string[]
     */
    public static function allowedSeparators(): array
    {
        return ['/', '-', '.', '_', '|', '*', ',', ' '];
    }
}
