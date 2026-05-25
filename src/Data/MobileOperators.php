<?php

namespace FallahAlireza\PersianTools\Data;

/**
 * Iranian mobile network operators and their prefixes (3 digits after leading 9).
 *
 * To add a new operator or prefix, simply add a new entry below.
 */
final class MobileOperators
{
    /**
     * @return array<string, string[]> Operator Name => list of 3-digit prefixes (e.g. '912')
     */
    public static function all(): array
    {
        return [
            'همراه اول (MCI)' => [
                '910', '911', '912', '913', '914', '915', '916', '917', '918', '919',
                '900', '901', '902', '903', '930', '933', '935', '936', '937', '938', '939',
                '941',
            ],
            'ایرانسل (MTN)' => [
                '920', '921', '922',
                '990', '991', '992', '993', '994',
            ],
            'رایتل' => [
                '932',
            ],
            'شاتل موبایل' => [
                '931', '934',
                '960', '961', '962', '963', '964',
            ],
            'اپراتورهای مجازی' => [
                '70', '73', '74', '75', '76', '77', '78', '79',
            ],
        ];
    }

    /**
     * Flat list of all valid prefixes (for validation use).
     *
     * @return string[]
     */
    public static function allPrefixes(): array
    {
        return array_merge(...array_values(self::all()));
    }
}
