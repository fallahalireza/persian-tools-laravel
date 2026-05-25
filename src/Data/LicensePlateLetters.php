<?php

namespace FallahAlireza\PersianTools\Data;

/**
 * Valid Persian letters used in Iranian vehicle license plates.
 *
 * To add or remove letters, simply edit the array below.
 */
final class LicensePlateLetters
{
    /**
     * @return string[]
     */
    public static function all(): array
    {
        return [
            'الف', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د',
            'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ط', 'ظ', 'ع',
            'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی',
        ];
    }
}
