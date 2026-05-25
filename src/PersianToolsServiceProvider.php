<?php

namespace FallahAlireza\PersianTools;

use FallahAlireza\PersianTools\Rules\IranianBankAccountNumber;
use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianCompanyId;
use FallahAlireza\PersianTools\Rules\IranianEconomicCode;
use FallahAlireza\PersianTools\Rules\IranianIban;
use FallahAlireza\PersianTools\Rules\IranianLicensePlate;
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Rules\IranianNationalId;
use FallahAlireza\PersianTools\Rules\IranianPhone;
use FallahAlireza\PersianTools\Rules\IranianPhoneAreaCode;
use FallahAlireza\PersianTools\Rules\IranianPostalCode;
use FallahAlireza\PersianTools\Rules\PersianAlpha;
use FallahAlireza\PersianTools\Rules\PersianAlphaEngNum;
use FallahAlireza\PersianTools\Rules\PersianAlphaNum;
use FallahAlireza\PersianTools\Rules\PersianDate;
use FallahAlireza\PersianTools\Rules\PersianDateBetween;
use FallahAlireza\PersianTools\Rules\PersianDay;
use FallahAlireza\PersianTools\Rules\PersianMonth;
use FallahAlireza\PersianTools\Rules\PersianNotAccept;
use FallahAlireza\PersianTools\Rules\PersianNum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class PersianToolsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/persian-tools.php', 'persian-tools');
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'persian-tools');

        $this->publishes([
            __DIR__.'/../config/persian-tools.php' => config_path('persian-tools.php'),
        ], 'persian-tools-config');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/persian-tools'),
        ], 'persian-tools-lang');

        if (config('persian-tools.register_rules', true)) {
            $this->registerValidationRules();
        }
    }

    protected function registerValidationRules(): void
    {
        $rules = [
            // Persian Text & Numbers
            'persian_alpha' => PersianAlpha::class,
            'persian_alpha_num' => PersianAlphaNum::class,
            'persian_alpha_eng_num' => PersianAlphaEngNum::class,
            'persian_num' => PersianNum::class,
            'persian_not_accept' => PersianNotAccept::class,

            // Persian Date
            'persian_date' => PersianDate::class,
            'persian_date_between' => PersianDateBetween::class,
            'persian_month' => PersianMonth::class,
            'persian_day' => PersianDay::class,

            // Phone Numbers
            'ir_mobile' => IranianMobile::class,
            'ir_phone' => IranianPhone::class,
            'ir_phone_area_code' => IranianPhoneAreaCode::class,

            // Identifiers
            'ir_national_id' => IranianNationalId::class,
            'ir_company_id' => IranianCompanyId::class,
            'ir_economic_code' => IranianEconomicCode::class,

            // Banking
            'ir_bank_card' => IranianBankCardNumber::class,
            'ir_iban' => IranianIban::class,
            'ir_bank_account' => IranianBankAccountNumber::class,

            // Other
            'ir_postal_code' => IranianPostalCode::class,
            'ir_license_plate' => IranianLicensePlate::class,
        ];

        foreach ($rules as $ruleName => $ruleClass) {
            Validator::extend($ruleName, static function ($attribute, $value, $parameters, $validator) use ($ruleClass) {
                return $ruleClass::fromParameters($parameters)->passes($attribute, $value);
            });

            Validator::replacer($ruleName, static function ($message, $attribute, $rule, $parameters) use ($ruleName) {
                return trans("persian-tools::validation.{$ruleName}", ['attribute' => $attribute]);
            });
        }
    }
}
