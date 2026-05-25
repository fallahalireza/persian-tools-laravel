<?php

namespace FallahAlireza\PersianTools\Tests\Unit;

use FallahAlireza\PersianTools\Rules\IranianLicensePlate;
use FallahAlireza\PersianTools\Rules\PersianAlpha;
use FallahAlireza\PersianTools\Rules\PersianDate;
use FallahAlireza\PersianTools\Rules\PersianDateBetween;
use FallahAlireza\PersianTools\Rules\PersianDay;
use FallahAlireza\PersianTools\Rules\PersianMonth;
use FallahAlireza\PersianTools\Rules\PersianNotAccept;
use FallahAlireza\PersianTools\Rules\PersianNum;
use FallahAlireza\PersianTools\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TextAndDateRulesTest extends TestCase
{
    // ─── Persian Text ─────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_persian_alpha(): void
    {
        $rule = new PersianAlpha;
        $this->assertTrue($rule->passes('f', 'سلام'));
        $this->assertTrue($rule->passes('f', 'علی‌رضا'));
        $this->assertFalse($rule->passes('f', 'Hello'));
        $this->assertFalse($rule->passes('f', 'سلام123'));
    }

    #[Test]
    public function it_validates_persian_num(): void
    {
        $rule = new PersianNum;
        $this->assertTrue($rule->passes('f', '۱۲۳۴۵'));
        $this->assertFalse($rule->passes('f', '12345'));
        $this->assertFalse($rule->passes('f', '۱۲abc'));
    }

    #[Test]
    public function it_validates_persian_not_accept(): void
    {
        $rule = new PersianNotAccept;
        $this->assertTrue($rule->passes('f', 'Hello 123'));
        $this->assertFalse($rule->passes('f', 'سلام'));
        $this->assertFalse($rule->passes('f', 'hello سلام'));
    }

    // ─── Persian Date ─────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_persian_date(): void
    {
        $rule = new PersianDate;
        $this->assertTrue($rule->passes('f', '1403/01/01'));
        $this->assertTrue($rule->passes('f', '1402/06/31'));
        $this->assertFalse($rule->passes('f', '2024/03/20'));  // Gregorian
        $this->assertFalse($rule->passes('f', '1403/13/01')); // invalid month
        $this->assertFalse($rule->passes('f', '1403/01/32')); // invalid day
    }

    #[Test]
    public function it_validates_persian_date_with_custom_separator(): void
    {
        $rule = new PersianDate(separator: '-');
        $this->assertTrue($rule->passes('f', '1403-01-01'));
        $this->assertFalse($rule->passes('f', '1403/01/01'));
    }

    #[Test]
    public function it_validates_persian_date_between(): void
    {
        $rule = new PersianDateBetween('1400/01/01', '1403/12/29');
        $this->assertTrue($rule->passes('f', '1402/06/15'));
        $this->assertFalse($rule->passes('f', '1399/12/29')); // before start
        $this->assertFalse($rule->passes('f', '1404/01/01')); // after end
    }

    #[Test]
    public function it_validates_persian_month_names(): void
    {
        $rule = new PersianMonth;
        $this->assertTrue($rule->passes('f', 'فروردین'));
        $this->assertTrue($rule->passes('f', 'اسفند'));
        $this->assertFalse($rule->passes('f', 'January'));
        $this->assertFalse($rule->passes('f', 'فروردن')); // typo
    }

    #[Test]
    public function it_validates_persian_day_names(): void
    {
        $rule = new PersianDay;
        $this->assertTrue($rule->passes('f', 'شنبه'));
        $this->assertTrue($rule->passes('f', 'جمعه'));
        $this->assertFalse($rule->passes('f', 'Saturday'));
    }

    // ─── License Plate ────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_iranian_license_plate(): void
    {
        $rule = new IranianLicensePlate;
        $this->assertTrue($rule->passes('f', '12الف34567'));
        $this->assertFalse($rule->passes('f', 'ABCD1234'));
        $this->assertFalse($rule->passes('f', '123456'));
    }

    #[Test]
    public function it_validates_motorcycle_plate_when_allowed(): void
    {
        $rule = new IranianLicensePlate(allowMotorcycle: true);
        $this->assertTrue($rule->passes('f', '123456789'));
    }
}
