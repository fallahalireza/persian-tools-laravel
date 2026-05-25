<?php

namespace FallahAlireza\PersianTools\Detectors;

use FallahAlireza\PersianTools\Data\BankBins;

/**
 * Detects the issuing bank from an Iranian bank card number.
 */
final class BankDetector
{
    /**
     * Detect the bank name from a card number.
     *
     * @param  string  $cardNumber  16-digit card number (separators are stripped automatically)
     * @return string|null Bank name in Persian, or null if unknown
     */
    public static function fromCardNumber(string $cardNumber): ?string
    {
        $clean = preg_replace('/\D/', '', $cardNumber) ?? '';

        if (strlen($clean) !== 16) {
            return null;
        }

        $bin = substr($clean, 0, 6);

        return BankBins::all()[$bin] ?? null;
    }

    /**
     * Check if the card belongs to a specific bank.
     *
     * @param  string  $bankName  Persian bank name (e.g. 'بانک ملی ایران')
     */
    public static function belongsToBank(string $cardNumber, string $bankName): bool
    {
        return self::fromCardNumber($cardNumber) === $bankName;
    }

    /**
     * Get all BINs belonging to a specific bank.
     *
     * @param  string  $bankName  Persian bank name
     * @return list<string> List of 6-digit BIN strings
     */
    public static function binsForBank(string $bankName): array
    {
        return array_map(
            'strval',
            array_keys(array_filter(
                BankBins::all(),
                fn (string $name) => $name === $bankName
            ))
        );
    }

    /**
     * Get a list of all unique bank names.
     *
     * @return string[]
     */
    public static function allBankNames(): array
    {
        return array_unique(array_values(BankBins::all()));
    }
}
