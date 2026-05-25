<?php

namespace FallahAlireza\PersianTools\Detectors;

use FallahAlireza\PersianTools\Data\MobileOperators;

/**
 * Detects the mobile network operator from an Iranian mobile number.
 */
final class MobileOperatorDetector
{
    /**
     * Detect the operator name from a mobile number.
     *
     * Accepts any format: 09123456789, +989123456789, 989123456789, 9123456789
     *
     * @return string|null Operator name in Persian, or null if unknown
     */
    public static function fromNumber(string $mobileNumber): ?string
    {
        $prefix = self::extractPrefix($mobileNumber);

        if ($prefix === null) {
            return null;
        }

        foreach (MobileOperators::all() as $operator => $prefixes) {
            if (in_array($prefix, $prefixes, true)) {
                return $operator;
            }
        }

        return null;
    }

    /**
     * Get all operator names.
     *
     * @return string[]
     */
    public static function allOperators(): array
    {
        return array_keys(MobileOperators::all());
    }

    /**
     * Get all prefixes belonging to a specific operator.
     *
     * @return string[]
     */
    public static function prefixesForOperator(string $operatorName): array
    {
        return MobileOperators::all()[$operatorName] ?? [];
    }

    /**
     * Extract the 3-digit prefix from a mobile number.
     */
    private static function extractPrefix(string $mobileNumber): ?string
    {
        $clean = preg_replace('/\D/', '', $mobileNumber) ?? '';

        // Strip country code and leading zero
        $clean = preg_replace('/^(?:0098|98|0)/', '', $clean) ?? '';

        if (strlen($clean) !== 10 || $clean[0] !== '9') {
            return null;
        }

        return substr($clean, 0, 3);
    }
}
