<?php

namespace FallahAlireza\PersianTools\Tests\Unit;

use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianIban;
use FallahAlireza\PersianTools\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BankingRulesTest extends TestCase
{
    // ─── Bank Card ────────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_correct_bank_card(): void
    {
        $rule = new IranianBankCardNumber();
        // Luhn-valid cards with known Iranian BIN prefixes
        $this->assertTrue($rule->passes('field', '6037991234567895')); // Bank Melli
        $this->assertTrue($rule->passes('field', '6104331234567894')); // Bank Mellat
    }

    #[Test]
    public function it_validates_card_with_separator(): void
    {
        $rule = new IranianBankCardNumber(separator: '-');
        $this->assertTrue($rule->passes('field', '6037-9912-3456-7895'));
    }

    #[Test]
    public function it_rejects_card_with_wrong_length(): void
    {
        $rule = new IranianBankCardNumber();
        $this->assertFalse($rule->passes('field', '603799123456789')); // 15 digits
    }

    #[Test]
    public function it_detects_bank_name_from_card(): void
    {
        $rule = new IranianBankCardNumber();
        $bank = $rule->detectBank('6037991234567895');
        $this->assertSame('بانک ملی ایران', $bank);
    }

    #[Test]
    public function it_returns_null_for_unknown_bank(): void
    {
        $rule = new IranianBankCardNumber();
        $this->assertNull($rule->detectBank('9999991234567890'));
    }

    // ─── IBAN ─────────────────────────────────────────────────────────────────

    #[Test]
    public function it_validates_correct_iban(): void
    {
        $rule = new IranianIban();
        $this->assertTrue($rule->passes('field', 'IR820540102680020817909002'));
    }

    #[Test]
    public function it_validates_iban_with_separator(): void
    {
        $rule = new IranianIban(separator: '-');
        $this->assertTrue($rule->passes('field', 'IR82-0540-1026-8002-0817-9090-02'));
    }

    #[Test]
    public function it_rejects_iban_wrong_length(): void
    {
        $rule = new IranianIban();
        $this->assertFalse($rule->passes('field', 'IR062960000000'));
    }

    #[Test]
    public function it_validates_iban_without_ir_prefix(): void
    {
        $rule = new IranianIban(withPrefix: false);
        $this->assertTrue($rule->passes('field', '820540102680020817909002'));
    }
}
