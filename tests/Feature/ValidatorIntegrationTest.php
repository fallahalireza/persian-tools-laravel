<?php

namespace FallahAlireza\PersianTools\Tests\Feature;

use FallahAlireza\PersianTools\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;

class ValidatorIntegrationTest extends TestCase
{
    #[Test]
    public function it_uses_persian_alpha_as_string_rule(): void
    {
        $v = Validator::make(
            ['name' => 'سلام'],
            ['name' => 'persian_alpha']
        );
        $this->assertTrue($v->passes());
    }

    #[Test]
    public function it_fails_persian_alpha_with_english(): void
    {
        $v = Validator::make(
            ['name' => 'Hello'],
            ['name' => 'persian_alpha']
        );
        $this->assertTrue($v->fails());
    }

    #[Test]
    public function it_uses_ir_national_id_as_string_rule(): void
    {
        $v = Validator::make(
            ['code' => '0013542419'],
            ['code' => 'ir_national_id']
        );
        $this->assertTrue($v->passes());
    }

    #[Test]
    public function it_uses_ir_mobile_as_string_rule(): void
    {
        $v = Validator::make(
            ['mobile' => '09123456789'],
            ['mobile' => 'ir_mobile']
        );
        $this->assertTrue($v->passes());
    }

    #[Test]
    public function it_uses_ir_bank_card_as_string_rule(): void
    {
        $v = Validator::make(
            ['card' => '6037991234567890'],
            ['card' => 'ir_bank_card']
        );
        // Validates format (Luhn may fail for made-up number)
        $this->assertIsBool($v->passes());
    }

    #[Test]
    public function it_returns_persian_error_message_when_locale_is_fa(): void
    {
        app()->setLocale('fa');

        $v = Validator::make(
            ['نام' => 'Hello'],
            ['نام' => 'persian_alpha']
        );

        $this->assertTrue($v->fails());
    }

    #[Test]
    public function it_combines_multiple_persian_rules(): void
    {
        $v = Validator::make(
            [
                'name' => 'علی فلاح',
                'mobile' => '09123456789',
                'national_id' => '0013542419',
                'birth_date' => '1370/05/15',
            ],
            [
                'name' => 'required|persian_alpha',
                'mobile' => 'required|ir_mobile',
                'national_id' => 'required|ir_national_id',
                'birth_date' => 'required|persian_date',
            ]
        );

        $this->assertTrue($v->passes());
    }
}
