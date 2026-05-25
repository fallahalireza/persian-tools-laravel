<?php

namespace FallahAlireza\PersianTools\Facades;

use FallahAlireza\PersianTools\Detectors\BankDetector;
use FallahAlireza\PersianTools\Detectors\MobileOperatorDetector;
use FallahAlireza\PersianTools\Detectors\PhoneAreaCodeDetector;

/**
 * PersianTools Facade.
 *
 * Provides a single entry point for all detector utilities.
 *
 * Usage:
 *   PersianTools::detectBank('6037991234567890');
 *   PersianTools::detectMobileOperator('09123456789');
 *   PersianTools::detectPhoneProvince('02112345678');
 */
final class PersianTools
{
    // ─── Bank ────────────────────────────────────────────────────────────────

    /**
     * Detect the bank name from a card number.
     *
     * @param  string  $cardNumber  16-digit card number (separators stripped automatically)
     * @return string|null e.g. 'بانک ملی ایران'
     */
    public static function detectBank(string $cardNumber): ?string
    {
        return BankDetector::fromCardNumber($cardNumber);
    }

    /**
     * Check if the card belongs to a specific bank.
     */
    public static function cardBelongsToBank(string $cardNumber, string $bankName): bool
    {
        return BankDetector::belongsToBank($cardNumber, $bankName);
    }

    /**
     * Get all BINs for a specific bank.
     *
     * @return string[]
     */
    public static function bankBins(string $bankName): array
    {
        return BankDetector::binsForBank($bankName);
    }

    /**
     * Get a list of all known bank names.
     *
     * @return string[]
     */
    public static function allBankNames(): array
    {
        return BankDetector::allBankNames();
    }

    // ─── Mobile ──────────────────────────────────────────────────────────────

    /**
     * Detect the mobile network operator from a mobile number.
     *
     * @param  string  $mobileNumber  Any format: 0912..., +9891..., 912...
     * @return string|null e.g. 'همراه اول (MCI)'
     */
    public static function detectMobileOperator(string $mobileNumber): ?string
    {
        return MobileOperatorDetector::fromNumber($mobileNumber);
    }

    /**
     * Get all known mobile operator names.
     *
     * @return string[]
     */
    public static function allMobileOperators(): array
    {
        return MobileOperatorDetector::allOperators();
    }

    // ─── Phone ───────────────────────────────────────────────────────────────

    /**
     * Detect the province from a landline phone number or area code.
     *
     * @param  string  $phoneOrAreaCode  e.g. '021' or '02112345678'
     * @return string|null e.g. 'تهران'
     */
    public static function detectPhoneProvince(string $phoneOrAreaCode): ?string
    {
        // If it looks like just an area code (3 digits)
        if (preg_match('/^0\d{2}$/', $phoneOrAreaCode)) {
            return PhoneAreaCodeDetector::fromAreaCode($phoneOrAreaCode);
        }

        return PhoneAreaCodeDetector::fromPhoneNumber($phoneOrAreaCode);
    }

    /**
     * Get all province names that have area codes.
     *
     * @return string[]
     */
    public static function allProvinces(): array
    {
        return PhoneAreaCodeDetector::allProvinces();
    }
}
