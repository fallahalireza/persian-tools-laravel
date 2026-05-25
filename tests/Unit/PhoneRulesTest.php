<?php

namespace FallahAlireza\PersianTools\Tests\Unit;

use FallahAlireza\PersianTools\Enums\MobileFormat;
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Rules\IranianPhone;
use FallahAlireza\PersianTools\Rules\IranianPhoneAreaCode;
use FallahAlireza\PersianTools\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PhoneRulesTest extends TestCase
{
    // ─── Mobile ───────────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_mobile_in_all_formats(): void
    {
        $rule = new IranianMobile();

        $this->assertTrue($rule->passes('f', '09123456789'));
        $this->assertTrue($rule->passes('f', '+989123456789'));
        $this->assertTrue($rule->passes('f', '989123456789'));
        $this->assertTrue($rule->passes('f', '00989123456789'));
        $this->assertTrue($rule->passes('f', '9123456789'));
    }

    #[Test]
    public function it_validates_mobile_with_specific_format(): void
    {
        $rule = new IranianMobile(format: MobileFormat::Zero);

        $this->assertTrue($rule->passes('f', '09123456789'));
        $this->assertFalse($rule->passes('f', '+989123456789'));
    }

    #[Test]
    public function it_rejects_invalid_mobile_prefix(): void
    {
        $rule = new IranianMobile();
        $this->assertFalse($rule->passes('f', '08123456789')); // 081 is not a mobile prefix
    }

    #[Test]
    public function it_accepts_persian_digit_mobile_when_convert_enabled(): void
    {
        $rule = new IranianMobile(convertPersianNumbers: true);
        $this->assertTrue($rule->passes('f', '۰۹۱۲۳۴۵۶۷۸۹'));
    }

    // ─── Landline Phone ───────────────────────────────────────────────────────

    #[Test]
    public function it_validates_phone_without_area_code(): void
    {
        $rule = new IranianPhone();
        $this->assertTrue($rule->passes('f', '12345678'));
        $this->assertFalse($rule->passes('f', '1234567')); // too short
    }

    #[Test]
    public function it_validates_phone_with_area_code(): void
    {
        $rule = new IranianPhone(withAreaCode: true);
        $this->assertTrue($rule->passes('f', '02112345678'));
    }

    #[Test]
    public function it_validates_phone_with_area_code_and_separator(): void
    {
        $rule = new IranianPhone(withAreaCode: true, areaCodeSeparator: '-');
        $this->assertTrue($rule->passes('f', '021-12345678'));
        $this->assertFalse($rule->passes('f', '02112345678')); // no separator
    }

    // ─── Area Code ────────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_area_codes(): void
    {
        $rule = new IranianPhoneAreaCode();
        $this->assertTrue($rule->passes('f', '021'));
        $this->assertTrue($rule->passes('f', '031'));
        $this->assertFalse($rule->passes('f', '099'));
        $this->assertFalse($rule->passes('f', '1234'));
    }
}
