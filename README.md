<div dir="rtl">

<p align="center">
  <h1 align="center">🇮🇷 Persian Tools for Laravel</h1>
    <p align="center">
        A powerful Laravel validation package for Persian/Iranian applications including national IDs, banking, mobile numbers, license plates, Jalali dates and more.
    </p>
  <p align="center">
    یک پکیج قدرتمند و جامع لاراول برای اعتبارسنجی متن فارسی، شناسه‌های ایرانی، بانکداری، تاریخ شمسی، پلاک خودرو و بیشتر
  </p>
</p>

<p align="center">
  <a href="https://php.net">
    <img src="https://img.shields.io/badge/PHP-%3E%3D8.2-blue" alt="PHP Version">
  </a>
  <a href="https://laravel.com">
    <img src="https://img.shields.io/badge/Laravel-11%2C12%2C13-red" alt="Laravel">
  </a>
  <a href="https://packagist.org/packages/fallahalireza/persian-tools-laravel">
    <img src="https://img.shields.io/packagist/v/fallahalireza/persian-tools-laravel" alt="Latest Version">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/badge/License-MIT-green" alt="License">
  </a>
</p>

---

### 🌍 Language / زبان

<p align="center">

  <a href="#-english-documentation">
    <img src="https://img.shields.io/badge/English-Documentation-blue?style=for-the-badge&logo=readthedocs" />
  </a>

  <a href="#-مستندات-فارسی">
    <img src="https://img.shields.io/badge/Persian-Documentation-red?style=for-the-badge&logo=readthedocs" />
 </a>

</p>

---

</div>

---

# 🇬🇧 English Documentation

## Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#️-configuration)
- [Usage](#-usage)
- [Validation Rules Reference](#-all-validation-rules)
    - [Persian Text & Numbers](#-persian-text--numbers)
    - [Persian Dates](#-persian-dates)
    - [Phone Numbers](#-phone-numbers)
    - [Identifiers](#-identifiers)
    - [Banking](#-banking)
    - [Other](#-other)
- [Enums Reference](#-enums-reference)
- [Localization](#-localization)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

This package provides a **rich set of Laravel validation rules** tailored for Persian/Iranian applications. Below is a quick comparison with other available packages:

| Feature | This Package | Others |
|---|---|---|
| کد اقتصادی (Economic Code) validation | ✅ | ❌ |
| پلاک خودرو (License Plate) validation | ✅ | ❌ |
| شماره حساب بانکی (Bank Account Number) | ✅ | ❌ |
| Bank detection from card BIN | ✅ | ❌ |
| Type-safe `PersianRule` Enum | ✅ | ❌ |
| `MobileFormat` Enum | ✅ | ❌ |
| Luhn algorithm for bank cards | ✅ | ✅ |
| Full IBAN (Sheba) checksum | ✅ | ✅ |
| National ID checksum | ✅ | ✅ |
| Persian digit normalization | ✅ | partial |
| Bilingual error messages (fa/en) | ✅ | partial |

---

## 📋 Requirements

| Dependency | Version |
|---|---|
| PHP | `^8.2` |
| Laravel | `^11.0`, `^12.0`, or `^13.0` |

---

## 📦 Installation

Install via Composer:

```bash
composer require fallahalireza/persian-tools-laravel
```

The package registers itself automatically via Laravel's package auto-discovery — no manual provider registration is needed.

### Publish Config (Optional)

```bash
php artisan vendor:publish --tag="persian-tools-config"
```

### Publish Language Files (Optional)

```bash
php artisan vendor:publish --tag="persian-tools-lang"
```

---

## ⚙️ Configuration

After publishing, edit `config/persian-tools.php`:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Register Rules as Laravel Validator Strings
    |--------------------------------------------------------------------------
    | When enabled, rules like 'ir_national_id', 'persian_alpha', etc.
    | can be used as strings in $rules arrays.
    */
    'register_rules' => true,

    /*
    |--------------------------------------------------------------------------
    | Accept Persian Digits Globally
    |--------------------------------------------------------------------------
    | When enabled, Persian numerals (۰-۹) are automatically converted
    | to their ASCII equivalents before validation.
    */
    'accept_persian_numbers' => false,
];
```

---

## 🚀 Usage

There are **three ways** to use this package:

### 1. String-based Rules (requires `register_rules = true`)

The simplest approach — use rule names as strings:

```php
$request->validate([
    'name'        => 'required|persian_alpha',
    'mobile'      => 'required|ir_mobile',
    'national_id' => 'required|ir_national_id',
    'birth_date'  => 'required|persian_date',
    'card_number' => 'required|ir_bank_card',
    'plate'       => 'required|ir_license_plate',
    'eco_code'    => 'required|ir_economic_code',
    'sheba'       => 'required|ir_iban',
    'postal_code' => 'required|ir_postal_code',
]);
```

### 2. Class-based Rules (always available)

Use rule classes directly for full control over parameters:

```php
use FallahAlireza\PersianTools\Rules\PersianAlpha;
use FallahAlireza\PersianTools\Rules\IranianNationalId;
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianPhone;
use FallahAlireza\PersianTools\Rules\IranianLicensePlate;
use FallahAlireza\PersianTools\Enums\MobileFormat;

$request->validate([
    'name'   => ['required', new PersianAlpha],
    'mobile' => ['required', new IranianMobile(format: MobileFormat::Zero)],
    'card'   => ['required', new IranianBankCardNumber(separator: '-')],
    'phone'  => ['required', new IranianPhone(withAreaCode: true, areaCodeSeparator: '-')],
    'plate'  => ['required', new IranianLicensePlate(allowMotorcycle: true)],
]);
```

### 3. Type-safe Enum (always available)

Leverage the `PersianRule` enum to avoid typos in rule names:

```php
use FallahAlireza\PersianTools\Enums\PersianRule;

$request->validate([
    'name'        => ['required', PersianRule::PersianAlpha->value],
    'mobile'      => ['required', PersianRule::IranianMobile->value],
    'national_id' => ['required', PersianRule::IranianNationalId->value],
]);
```

---

## 📋 All Validation Rules

### 🔤 Persian Text & Numbers

| String Rule | Class | Description | Valid Example | Invalid Example |
|---|---|---|---|---|
| `persian_alpha` | `PersianAlpha` | Persian letters, punctuation & spaces | `سلام علی‌رضا` | `Hello` |
| `persian_alpha_num` | `PersianAlphaNum` | Persian letters + Persian digits | `سلام۱۲۳` | `Hello 123` |
| `persian_alpha_eng_num` | `PersianAlphaEngNum` | Persian letters + Persian/English digits | `سلام123` | `Hello` |
| `persian_num` | `PersianNum` | Persian digits only | `۱۲۳۴۵` | `12345` |
| `persian_not_accept` | `PersianNotAccept` | Reject any Persian content | `Hello 123` | `سلام` |

---

### 📅 Persian Dates

| String Rule | Parameters | Description |
|---|---|---|
| `persian_date` | `separator`, `convertPersianNumbers` | Valid Jalali (Shamsi) date |
| `persian_date_between` | `startDate`, `endDate`, `separator`, `convertPersianNumbers` | Date within a range (exclusive) |
| `persian_month` | — | Valid Persian month name |
| `persian_day` | — | Valid Persian day of week name |

**Examples:**

```php
use FallahAlireza\PersianTools\Rules\PersianDate;
use FallahAlireza\PersianTools\Rules\PersianDateBetween;

// Default separator is '/'  →  e.g. 1403/06/31
new PersianDate()

// Custom separator
new PersianDate(separator: '-')  // e.g. 1403-06-31

// Accept Persian digits like ۱۴۰۳/۰۶/۳۱
new PersianDate(convertPersianNumbers: true)

// Validate date between two dates (not inclusive)
new PersianDateBetween('1400/01/01', '1403/12/29')
```

---

### 📱 Phone Numbers

| String Rule | Class | Parameters | Valid Examples |
|---|---|---|---|
| `ir_mobile` | `IranianMobile` | `format`, `convertPersianNumbers` | `09123456789`, `+989123456789` |
| `ir_phone` | `IranianPhone` | `withAreaCode`, `areaCodeSeparator`, `withCountryCodeFormat`, `convertPersianNumbers` | `02112345678` |
| `ir_phone_area_code` | `IranianPhoneAreaCode` | `convertPersianNumbers` | `021`, `031`, `044` |

**Mobile Format options:**

```php
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Enums\MobileFormat;

new IranianMobile(format: MobileFormat::All)       // any format (default)
new IranianMobile(format: MobileFormat::Zero)      // 09xxxxxxxxx only
new IranianMobile(format: MobileFormat::PlusCode)  // +98xxxxxxxxx only
new IranianMobile(format: MobileFormat::ZeroCode)  // 0098xxxxxxxxx only
new IranianMobile(format: MobileFormat::Code)      // 98xxxxxxxxx only
new IranianMobile(format: MobileFormat::Normal)    // 9xxxxxxxxx only
```

**Phone with area code:**

```php
use FallahAlireza\PersianTools\Rules\IranianPhone;

new IranianPhone(withAreaCode: true)                              // 02112345678
new IranianPhone(withAreaCode: true, areaCodeSeparator: '-')      // 021-12345678
new IranianPhone(withAreaCode: false)                             // 12345678 (no area code)
```

---

### 🪪 Identifiers

| String Rule | Class | Description | Valid Example |
|---|---|---|---|
| `ir_national_id` | `IranianNationalId` | 10-digit National ID with checksum | `0013542419` |
| `ir_company_id` | `IranianCompanyId` | Legal Entity National ID (شناسه ملی اشخاص حقوقی) | `14007650912` |
| `ir_economic_code` | `IranianEconomicCode` | 14-digit Economic Code (کد اقتصادی) ⭐ | `14004800101010` |

---

### 🏦 Banking

| String Rule | Class | Description |
|---|---|---|
| `ir_bank_card` | `IranianBankCardNumber` | 16-digit bank card (Luhn algorithm + BIN detection) |
| `ir_iban` | `IranianIban` | IBAN / Sheba number with full checksum validation |
| `ir_bank_account` | `IranianBankAccountNumber` | Bank account number ⭐ |

**Advanced banking usage:**

```php
use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianIban;

// Detect bank name from card number
$rule = new IranianBankCardNumber();
$bank = $rule->detectBank('6037991234567890');
// Returns: "بانک ملی ایران"

// Accept card with dash separator: 6037-9912-3456-7890
new IranianBankCardNumber(separator: '-')

// IBAN without 'IR' prefix
new IranianIban(withPrefix: false)

// IBAN with 'IR' prefix (default)
new IranianIban(withPrefix: true)
```

---

### 📍 Other

| String Rule | Class | Parameters | Description |
|---|---|---|---|
| `ir_postal_code` | `IranianPostalCode` | `separator` | 10-digit Iranian postal code |
| `ir_license_plate` | `IranianLicensePlate` | `allowMotorcycle` | Car & motorcycle license plate ⭐ |

**Examples:**

```php
use FallahAlireza\PersianTools\Rules\IranianLicensePlate;
use FallahAlireza\PersianTools\Rules\IranianPostalCode;

// Standard car plate only: e.g. 12الف34567
new IranianLicensePlate()

// Also accept motorcycle plates: e.g. 123456789
new IranianLicensePlate(allowMotorcycle: true)

// Postal code without separator: 1234567890
new IranianPostalCode()

// Postal code with separator: 12345-67890
new IranianPostalCode(separator: '-')
```

---

## 🔢 Enums Reference

### `PersianRule` Enum

Type-safe enum mapping all rule names:

```php
use FallahAlireza\PersianTools\Enums\PersianRule;

PersianRule::PersianAlpha->value        // 'persian_alpha'
PersianRule::PersianAlphaNum->value     // 'persian_alpha_num'
PersianRule::PersianNum->value          // 'persian_num'
PersianRule::PersianNotAccept->value    // 'persian_not_accept'
PersianRule::IranianMobile->value       // 'ir_mobile'
PersianRule::IranianPhone->value        // 'ir_phone'
PersianRule::IranianNationalId->value   // 'ir_national_id'
PersianRule::IranianCompanyId->value    // 'ir_company_id'
PersianRule::IranianEconomicCode->value // 'ir_economic_code'
PersianRule::IranianBankCard->value     // 'ir_bank_card'
PersianRule::IranianIban->value         // 'ir_iban'
PersianRule::IranianBankAccount->value  // 'ir_bank_account'
PersianRule::IranianPostalCode->value   // 'ir_postal_code'
PersianRule::IranianLicensePlate->value // 'ir_license_plate'
PersianRule::PersianDate->value         // 'persian_date'
PersianRule::PersianMonth->value        // 'persian_month'
PersianRule::PersianDay->value          // 'persian_day'
```

### `MobileFormat` Enum

```php
use FallahAlireza\PersianTools\Enums\MobileFormat;

MobileFormat::All       // Any format (default)
MobileFormat::Zero      // Starting with 0: 09...
MobileFormat::PlusCode  // Starting with +98: +989...
MobileFormat::ZeroCode  // Starting with 0098: 00989...
MobileFormat::Code      // Starting with 98: 989...
MobileFormat::Normal    // Starting with 9: 9...
```

---

## 🌐 Localization

The package ships with **Persian (fa)** and **English (en)** error messages.

Set the default locale in `config/app.php`:

```php
'locale' => 'fa', // or 'en'
```

Override per request:

```php
app()->setLocale('fa');
```

To customize messages, publish the language files and edit:

```
lang/
  fa/persian-tools.php
  en/persian-tools.php
```

---

## 🧪 Testing

```bash
composer test
```

Run with coverage:

```bash
composer test-coverage
```

Run static analysis:

```bash
composer analyse
```

Format code:

```bash
composer format
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit your changes: `git commit -m 'Add my feature'`
4. Push to the branch: `git push origin feature/my-feature`
5. Open a Pull Request

Please make sure all tests pass and code follows PSR-12 standards before submitting.

---

## 📄 License

The MIT License (MIT). See [LICENSE](LICENSE) for more information.

**Developed with ❤️ by [Alireza Fallah](https://github.com/fallahalireza)**

---
---

<div dir="rtl">

# 🇮🇷 مستندات فارسی

## فهرست مطالب

- [ویژگی‌ها](#-ویژگیها)
- [پیش‌نیازها](#-پیشنیازها)
- [نصب](#-نصب)
- [پیکربندی](#️-پیکربندی)
- [نحوه استفاده](#-نحوه-استفاده)
- [مرجع قوانین اعتبارسنجی](#-تمام-قوانین-اعتبارسنجی)
    - [متن و اعداد فارسی](#-متن-و-اعداد-فارسی)
    - [تاریخ شمسی](#-تاریخ-شمسی)
    - [شماره تلفن](#-شماره-تلفن)
    - [شناسه‌ها](#-شناسهها)
    - [بانکداری](#-بانکداری)
    - [سایر](#-سایر)
- [مرجع Enum‌ها](#-مرجع-enumها)
- [بومی‌سازی](#-بومیسازی)
- [تست‌ها](#-تستها)
- [مشارکت](#-مشارکت)
- [مجوز](#-مجوز)

---

## ✨ ویژگی‌ها

این پکیج مجموعه‌ای غنی از **قوانین اعتبارسنجی لاراول** مختص برنامه‌های فارسی/ایرانی ارائه می‌دهد. مقایسه با سایر پکیج‌های مشابه:

| ویژگی | این پکیج | سایرین |
|---|---|---|
| اعتبارسنجی کد اقتصادی | ✅ | ❌ |
| اعتبارسنجی پلاک خودرو | ✅ | ❌ |
| اعتبارسنجی شماره حساب بانکی | ✅ | ❌ |
| تشخیص بانک از BIN کارت | ✅ | ❌ |
| `PersianRule` Enum ایمن از نظر نوع | ✅ | ❌ |
| `MobileFormat` Enum | ✅ | ❌ |
| الگوریتم Luhn برای کارت بانکی | ✅ | ✅ |
| اعتبارسنجی کامل IBAN (شبا) | ✅ | ✅ |
| اعتبارسنجی کد ملی با checksum | ✅ | ✅ |
| تبدیل اعداد فارسی به انگلیسی | ✅ | ناقص |
| پیام‌های خطا دو زبانه (fa/en) | ✅ | ناقص |

---

## 📋 پیش‌نیازها

| وابستگی | نسخه |
|---|---|
| PHP | `^8.2` |
| Laravel | `^11.0`، `^12.0`، یا `^13.0` |

---

## 📦 نصب

از طریق Composer نصب کنید:

```bash
composer require fallahalireza/persian-tools-laravel
```

پکیج از طریق قابلیت auto-discovery لاراول به صورت خودکار ثبت می‌شود — نیازی به ثبت دستی provider نیست.

### انتشار فایل پیکربندی (اختیاری)

```bash
php artisan vendor:publish --tag="persian-tools-config"
```

### انتشار فایل‌های زبان (اختیاری)

```bash
php artisan vendor:publish --tag="persian-tools-lang"
```

---

## ⚙️ پیکربندی

پس از انتشار، فایل `config/persian-tools.php` را ویرایش کنید:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | ثبت قوانین به صورت رشته در validator لاراول
    |--------------------------------------------------------------------------
    | در صورت فعال بودن، می‌توانید قوانین مانند 'ir_national_id'، 'persian_alpha'
    | و غیره را به صورت رشته در آرایه $rules استفاده کنید.
    */
    'register_rules' => true,

    /*
    |--------------------------------------------------------------------------
    | پذیرش اعداد فارسی به صورت سراسری
    |--------------------------------------------------------------------------
    | در صورت فعال بودن، اعداد فارسی (۰-۹) قبل از اعتبارسنجی
    | به معادل ASCII تبدیل می‌شوند.
    */
    'accept_persian_numbers' => false,
];
```

---

## 🚀 نحوه استفاده

سه روش برای استفاده از این پکیج وجود دارد:

### روش اول: استفاده رشته‌ای (نیاز به `register_rules = true`)

ساده‌ترین روش — استفاده از نام قوانین به صورت رشته:

```php
$request->validate([
    'name'        => 'required|persian_alpha',
    'mobile'      => 'required|ir_mobile',
    'national_id' => 'required|ir_national_id',
    'birth_date'  => 'required|persian_date',
    'card_number' => 'required|ir_bank_card',
    'plate'       => 'required|ir_license_plate',
    'eco_code'    => 'required|ir_economic_code',
    'sheba'       => 'required|ir_iban',
    'postal_code' => 'required|ir_postal_code',
]);
```

### روش دوم: استفاده از کلاس (همیشه در دسترس)

استفاده مستقیم از کلاس‌های قانون برای کنترل کامل پارامترها:

```php
use FallahAlireza\PersianTools\Rules\PersianAlpha;
use FallahAlireza\PersianTools\Rules\IranianNationalId;
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianPhone;
use FallahAlireza\PersianTools\Rules\IranianLicensePlate;
use FallahAlireza\PersianTools\Enums\MobileFormat;

$request->validate([
    'name'   => ['required', new PersianAlpha],
    'mobile' => ['required', new IranianMobile(format: MobileFormat::Zero)],
    'card'   => ['required', new IranianBankCardNumber(separator: '-')],
    'phone'  => ['required', new IranianPhone(withAreaCode: true, areaCodeSeparator: '-')],
    'plate'  => ['required', new IranianLicensePlate(allowMotorcycle: true)],
]);
```

### روش سوم: استفاده از Enum ایمن (همیشه در دسترس)

از `PersianRule` enum برای جلوگیری از اشتباه تایپی استفاده کنید:

```php
use FallahAlireza\PersianTools\Enums\PersianRule;

$request->validate([
    'name'        => ['required', PersianRule::PersianAlpha->value],
    'mobile'      => ['required', PersianRule::IranianMobile->value],
    'national_id' => ['required', PersianRule::IranianNationalId->value],
]);
```

---

## 📋 تمام قوانین اعتبارسنجی

### 🔤 متن و اعداد فارسی

| قانون رشته‌ای | کلاس | توضیح | مثال معتبر | مثال نامعتبر |
|---|---|---|---|---|
| `persian_alpha` | `PersianAlpha` | حروف فارسی، علائم نگارشی و فاصله | `سلام علی‌رضا` | `Hello` |
| `persian_alpha_num` | `PersianAlphaNum` | حروف فارسی + اعداد فارسی | `سلام۱۲۳` | `Hello 123` |
| `persian_alpha_eng_num` | `PersianAlphaEngNum` | حروف فارسی + اعداد فارسی/انگلیسی | `سلام123` | `Hello` |
| `persian_num` | `PersianNum` | فقط اعداد فارسی | `۱۲۳۴۵` | `12345` |
| `persian_not_accept` | `PersianNotAccept` | رد هر محتوای فارسی | `Hello 123` | `سلام` |

---

### 📅 تاریخ شمسی

| قانون رشته‌ای | پارامترها | توضیح |
|---|---|---|
| `persian_date` | `separator`، `convertPersianNumbers` | تاریخ شمسی معتبر |
| `persian_date_between` | `startDate`، `endDate`، `separator`، `convertPersianNumbers` | تاریخ در محدوده (غیر شامل) |
| `persian_month` | — | نام ماه شمسی معتبر |
| `persian_day` | — | نام روز هفته فارسی معتبر |

**مثال‌ها:**

```php
use FallahAlireza\PersianTools\Rules\PersianDate;
use FallahAlireza\PersianTools\Rules\PersianDateBetween;

// جداکننده پیش‌فرض '/' است — مثلاً ۱۴۰۳/۰۶/۳۱
new PersianDate()

// جداکننده سفارشی
new PersianDate(separator: '-')  // مثلاً ۱۴۰۳-۰۶-۳۱

// پذیرش اعداد فارسی مانند ۱۴۰۳/۰۶/۳۱
new PersianDate(convertPersianNumbers: true)

// اعتبارسنجی تاریخ بین دو تاریخ (غیر شامل)
new PersianDateBetween('1400/01/01', '1403/12/29')
```

---

### 📱 شماره تلفن

| قانون رشته‌ای | کلاس | پارامترها | مثال معتبر |
|---|---|---|---|
| `ir_mobile` | `IranianMobile` | `format`، `convertPersianNumbers` | `09123456789`، `+989123456789` |
| `ir_phone` | `IranianPhone` | `withAreaCode`، `areaCodeSeparator`، `withCountryCodeFormat`، `convertPersianNumbers` | `02112345678` |
| `ir_phone_area_code` | `IranianPhoneAreaCode` | `convertPersianNumbers` | `021`، `031`، `044` |

**گزینه‌های فرمت موبایل:**

```php
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Enums\MobileFormat;

new IranianMobile(format: MobileFormat::All)       // هر فرمتی (پیش‌فرض)
new IranianMobile(format: MobileFormat::Zero)      // فقط 09xxxxxxxxx
new IranianMobile(format: MobileFormat::PlusCode)  // فقط +98xxxxxxxxx
new IranianMobile(format: MobileFormat::ZeroCode)  // فقط 0098xxxxxxxxx
new IranianMobile(format: MobileFormat::Code)      // فقط 98xxxxxxxxx
new IranianMobile(format: MobileFormat::Normal)    // فقط 9xxxxxxxxx
```

**تلفن ثابت با کد منطقه:**

```php
use FallahAlireza\PersianTools\Rules\IranianPhone;

new IranianPhone(withAreaCode: true)                              // 02112345678
new IranianPhone(withAreaCode: true, areaCodeSeparator: '-')      // 021-12345678
new IranianPhone(withAreaCode: false)                             // 12345678 (بدون کد منطقه)
```

---

### 🪪 شناسه‌ها

| قانون رشته‌ای | کلاس | توضیح | مثال معتبر |
|---|---|---|---|
| `ir_national_id` | `IranianNationalId` | کد ملی ۱۰ رقمی با checksum | `0013542419` |
| `ir_company_id` | `IranianCompanyId` | شناسه ملی اشخاص حقوقی | `14007650912` |
| `ir_economic_code` | `IranianEconomicCode` | کد اقتصادی ۱۴ رقمی ⭐ | `14004800101010` |

---

### 🏦 بانکداری

| قانون رشته‌ای | کلاس | توضیح |
|---|---|---|
| `ir_bank_card` | `IranianBankCardNumber` | کارت بانکی ۱۶ رقمی (الگوریتم Luhn + تشخیص BIN) |
| `ir_iban` | `IranianIban` | شماره شبا با اعتبارسنجی checksum کامل |
| `ir_bank_account` | `IranianBankAccountNumber` | شماره حساب بانکی ⭐ |

**استفاده پیشرفته از بانکداری:**

```php
use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianIban;

// تشخیص نام بانک از شماره کارت
$rule = new IranianBankCardNumber();
$bank = $rule->detectBank('6037991234567890');
// خروجی: "بانک ملی ایران"

// پذیرش کارت با جداکننده خط تیره: 6037-9912-3456-7890
new IranianBankCardNumber(separator: '-')

// شبا بدون پیشوند 'IR'
new IranianIban(withPrefix: false)

// شبا با پیشوند 'IR' (پیش‌فرض)
new IranianIban(withPrefix: true)
```

---

### 📍 سایر

| قانون رشته‌ای | کلاس | پارامترها | توضیح |
|---|---|---|---|
| `ir_postal_code` | `IranianPostalCode` | `separator` | کد پستی ۱۰ رقمی |
| `ir_license_plate` | `IranianLicensePlate` | `allowMotorcycle` | پلاک خودرو و موتورسیکلت ⭐ |

**مثال‌ها:**

```php
use FallahAlireza\PersianTools\Rules\IranianLicensePlate;
use FallahAlireza\PersianTools\Rules\IranianPostalCode;

// فقط پلاک اتومبیل: مثلاً ۱۲الف۳۴۵۶۷
new IranianLicensePlate()

// پذیرش پلاک موتورسیکلت هم: مثلاً ۱۲۳۴۵۶۷۸۹
new IranianLicensePlate(allowMotorcycle: true)

// کد پستی بدون جداکننده: 1234567890
new IranianPostalCode()

// کد پستی با جداکننده: 12345-67890
new IranianPostalCode(separator: '-')
```

---

## 🔢 مرجع Enum‌ها

### `PersianRule` Enum

Enum ایمن از نظر نوع که نام تمام قوانین را نگاشت می‌کند:

```php
use FallahAlireza\PersianTools\Enums\PersianRule;

PersianRule::PersianAlpha->value        // 'persian_alpha'
PersianRule::PersianAlphaNum->value     // 'persian_alpha_num'
PersianRule::PersianNum->value          // 'persian_num'
PersianRule::PersianNotAccept->value    // 'persian_not_accept'
PersianRule::IranianMobile->value       // 'ir_mobile'
PersianRule::IranianPhone->value        // 'ir_phone'
PersianRule::IranianNationalId->value   // 'ir_national_id'
PersianRule::IranianCompanyId->value    // 'ir_company_id'
PersianRule::IranianEconomicCode->value // 'ir_economic_code'
PersianRule::IranianBankCard->value     // 'ir_bank_card'
PersianRule::IranianIban->value         // 'ir_iban'
PersianRule::IranianBankAccount->value  // 'ir_bank_account'
PersianRule::IranianPostalCode->value   // 'ir_postal_code'
PersianRule::IranianLicensePlate->value // 'ir_license_plate'
PersianRule::PersianDate->value         // 'persian_date'
PersianRule::PersianMonth->value        // 'persian_month'
PersianRule::PersianDay->value          // 'persian_day'
```

### `MobileFormat` Enum

```php
use FallahAlireza\PersianTools\Enums\MobileFormat;

MobileFormat::All       // هر فرمتی (پیش‌فرض)
MobileFormat::Zero      // شروع با 0: 09...
MobileFormat::PlusCode  // شروع با +98: +989...
MobileFormat::ZeroCode  // شروع با 0098: 00989...
MobileFormat::Code      // شروع با 98: 989...
MobileFormat::Normal    // شروع با 9: 9...
```

---

## 🌐 بومی‌سازی

این پکیج با پیام‌های خطای **فارسی (fa)** و **انگلیسی (en)** ارائه می‌شود.

زبان پیش‌فرض را در `config/app.php` تنظیم کنید:

```php
'locale' => 'fa', // یا 'en'
```

تغییر به ازای درخواست:

```php
app()->setLocale('fa');
```

برای سفارشی‌سازی پیام‌ها، فایل‌های زبان را منتشر کرده و ویرایش نمایید:

```
lang/
  fa/persian-tools.php
  en/persian-tools.php
```

---

## 🧪 تست‌ها

```bash
composer test
```

اجرا با پوشش کد:

```bash
composer test-coverage
```

تحلیل استاتیک:

```bash
composer analyse
```

فرمت کد:

```bash
composer format
```

---

## 🤝 مشارکت

مشارکت شما خوشایند است! لطفاً مراحل زیر را دنبال کنید:

1. ریپازیتوری را Fork کنید
2. یک شاخه ویژگی بسازید: `git checkout -b feature/my-feature`
3. تغییرات خود را Commit کنید: `git commit -m 'Add my feature'`
4. شاخه را Push کنید: `git push origin feature/my-feature`
5. یک Pull Request باز کنید

لطفاً قبل از ارسال مطمئن شوید که تمام تست‌ها pass می‌شوند و کد از استانداردهای PSR-12 پیروی می‌کند.

---

## 📄 مجوز

مجوز MIT. برای اطلاعات بیشتر [LICENSE](LICENSE) را ببینید.

**توسعه داده شده با ❤️ توسط [علیرضا فلاح](https://github.com/fallahalireza)**

</div>
