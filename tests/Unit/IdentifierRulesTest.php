<?php

namespace FallahAlireza\PersianTools\Tests\Unit;

use FallahAlireza\PersianTools\Rules\IranianCompanyId;
use FallahAlireza\PersianTools\Rules\IranianEconomicCode;
use FallahAlireza\PersianTools\Rules\IranianNationalId;
use FallahAlireza\PersianTools\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class IdentifierRulesTest extends TestCase
{
    // ─── National ID ──────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_correct_national_ids(): void
    {
        $rule = new IranianNationalId();

        $this->assertTrue($rule->passes('field', '0013542419')); // checksum: valid
        $this->assertTrue($rule->passes('field', '0075481901')); // checksum: valid
        $this->assertTrue($rule->passes('field', '4280026351')); // checksum: valid
    }

    #[Test]
    public function it_rejects_national_id_with_all_same_digits(): void
    {
        $rule = new IranianNationalId();

        foreach (['0000000000', '1111111111', '9999999999'] as $id) {
            $this->assertFalse($rule->passes('field', $id), "Failed asserting {$id} is invalid.");
        }
    }

    #[Test]
    public function it_rejects_national_id_wrong_checksum(): void
    {
        $rule = new IranianNationalId();
        $this->assertFalse($rule->passes('field', '0013542418')); // last digit changed
    }

    #[Test]
    public function it_accepts_persian_digit_national_id_when_convert_enabled(): void
    {
        $rule = new IranianNationalId(convertPersianNumbers: true);
        $this->assertTrue($rule->passes('field', '۰۰۱۳۵۴۲۴۱۹'));
    }

    // ─── Company ID ───────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_correct_company_ids(): void
    {
        $rule = new IranianCompanyId();
        $this->assertTrue($rule->passes('field', '10000000009')); // checksum: valid
        $this->assertTrue($rule->passes('field', '10861472140')); // checksum: valid
        $this->assertTrue($rule->passes('field', '14007650917')); // checksum: valid
    }

    #[Test]
    public function it_rejects_company_id_with_wrong_length(): void
    {
        $rule = new IranianCompanyId();
        $this->assertFalse($rule->passes('field', '1234567890'));   // 10 digits
        $this->assertFalse($rule->passes('field', '123456789012')); // 12 digits
    }

    // ─── Economic Code ────────────────────────────────────────────────────────

    #[Test]
    public function it_rejects_economic_code_wrong_length(): void
    {
        $rule = new IranianEconomicCode();
        $this->assertFalse($rule->passes('field', '1234567890123'));  // 13 digits
        $this->assertFalse($rule->passes('field', '123456789012345')); // 15 digits
    }

    #[Test]
    public function it_rejects_all_same_digit_economic_code(): void
    {
        $rule = new IranianEconomicCode();
        $this->assertFalse($rule->passes('field', '00000000000000'));
    }
}
