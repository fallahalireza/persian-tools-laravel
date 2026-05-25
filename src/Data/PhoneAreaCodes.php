<?php

namespace FallahAlireza\PersianTools\Data;

/**
 * Iranian telephone area codes by province.
 *
 * To add or update area codes, simply edit the array below.
 */
final class PhoneAreaCodes
{
    /**
     * @return array<string, string[]> Province => list of area codes
     */
    public static function all(): array
    {
        return [
            'مازندران' => ['011'],
            'گیلان' => ['013'],
            'گلستان' => ['017'],
            'تهران' => ['021'],
            'سمنان' => ['023'],
            'زنجان' => ['024'],
            'قم' => ['025'],
            'البرز' => ['026'],
            'قزوین' => ['028'],
            'اصفهان' => ['031'],
            'کرمان' => ['034'],
            'یزد' => ['035'],
            'چهارمحال بختیاری' => ['038'],
            'آذربایجان شرقی' => ['041'],
            'آذربایجان غربی' => ['044'],
            'اردبیل' => ['045'],
            'خراسان رضوی' => ['051'],
            'سیستان بلوچستان' => ['054'],
            'خراسان جنوبی' => ['056'],
            'خراسان شمالی' => ['058'],
            'خوزستان' => ['061'],
            'لرستان' => ['066'],
            'فارس' => ['071'],
            'کهگیلویه بویر احمد' => ['074'],
            'هرمزگان' => ['076'],
            'بوشهر' => ['077'],
            'همدان' => ['081'],
            'کرمانشاه' => ['083'],
            'ایلام' => ['084'],
            'مرکزی' => ['086'],
            'کردستان' => ['087'],
        ];
    }

    /**
     * Flat list of all area codes (for validation use).
     *
     * @return string[]
     */
    public static function allCodes(): array
    {
        return array_merge(...array_values(self::all()));
    }
}
