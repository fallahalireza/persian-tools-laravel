<?php

namespace FallahAlireza\PersianTools\Detectors;

use FallahAlireza\PersianTools\Data\PhoneAreaCodes;

/**
 * Detects the province from an Iranian landline area code or full phone number.
 */
final class PhoneAreaCodeDetector
{
    /**
     * Detect province from a 3-digit area code (e.g. '021').
     *
     * @return string|null Province name in Persian, or null if unknown
     */
    public static function fromAreaCode(string $areaCode): ?string
    {
        foreach (PhoneAreaCodes::all() as $province => $codes) {
            if (in_array($areaCode, $codes, true)) {
                return $province;
            }
        }

        return null;
    }

    /**
     * Detect province from a full phone number (e.g. '02112345678').
     *
     * @return string|null Province name in Persian, or null if unknown
     */
    public static function fromPhoneNumber(string $phoneNumber): ?string
    {
        $clean = preg_replace('/\D/', '', $phoneNumber) ?? '';

        // Strip country code
        $clean = preg_replace('/^(?:0098|98)/', '0', $clean) ?? '';

        if (strlen($clean) < 3 || $clean[0] !== '0') {
            return null;
        }

        $areaCode = substr($clean, 0, 3);

        return self::fromAreaCode($areaCode);
    }

    /**
     * Get all province names.
     *
     * @return string[]
     */
    public static function allProvinces(): array
    {
        return array_keys(PhoneAreaCodes::all());
    }
}
