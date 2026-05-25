# 🇮🇷 Persian Tools for Laravel

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11,12,13-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

A **powerful** and **comprehensive** Laravel validation package for Persian text, Iranian identifiers, banking, dates, license plates, and more.

**Developed by Alireza Fallah** — `fallahalireza/persian-tools-laravel`

---

## 📦 Installation

```bash
composer require fallahalireza/persian-tools-laravel
```

### Publish config (optional)

```bash
php artisan vendor:publish --tag="persian-tools-config"
```

### Publish language files (optional)

```bash
php artisan vendor:publish --tag="persian-tools-lang"
```

---

## 🗂️ Package Structure

```
src/
├── Data/                        ← All static lookup data (easy to extend)
│   ├── BankBins.php             ← Bank BIN prefixes
│   ├── MobileOperators.php      ← Mobile operator prefixes
│   ├── PhoneAreaCodes.php       ← Landline area codes by province
│   ├── LicensePlateLetters.php  ← Valid Persian plate letters
│   └── PersianCalendar.php      ← Month names, day names, days-per-month
│
├── Detectors/                   ← Detection utilities (independent of validation)
│   ├── BankDetector.php         ← Detect bank from card number
│   ├── MobileOperatorDetector.php ← Detect operator from mobile number
│   └── PhoneAreaCodeDetector.php  ← Detect province from area code
│
├── Rules/                       ← Laravel validation rules
│   └── ...
│
├── Facades/
│   └── PersianTools.php         ← Single entry point for all detectors
│
├── Enums/
│   ├── MobileFormat.php
│   └── PersianRule.php
│
├── Concerns/
│   └── NormalizesPersianNumbers.php
│
└── PersianToolsServiceProvider.php
```

---

## 🔍 Detection (New!)

Beyond validation, you can use detectors to **identify** information:

### Detect Bank from Card Number

```php
use FallahAlireza\PersianTools\Detectors\BankDetector;
// or via facade:
use FallahAlireza\PersianTools\Facades\PersianTools;

BankDetector::fromCardNumber('6037991234567890');
// → 'بانک ملی ایران'

BankDetector::fromCardNumber('6037-9912-3456-7890'); // separators stripped
// → 'بانک ملی ایران'

BankDetector::belongsToBank('6037991234567890', 'بانک ملی ایران');
// → true

BankDetector::binsForBank('بانک ملت');
// → ['610433', '991975']

BankDetector::allBankNames();
// → ['بانک ملی ایران', 'بانک سپه', ...]

// Facade shorthand:
PersianTools::detectBank('6037991234567890');
// → 'بانک ملی ایران'
```

### Detect Mobile Operator

```php
use FallahAlireza\PersianTools\Detectors\MobileOperatorDetector;

MobileOperatorDetector::fromNumber('09123456789');
// → 'همراه اول (MCI)'

MobileOperatorDetector::fromNumber('+989301234567');
// → 'همراه اول (MCI)'

MobileOperatorDetector::fromNumber('09201234567');
// → 'ایرانسل (MTN)'

MobileOperatorDetector::allOperators();
// → ['همراه اول (MCI)', 'ایرانسل (MTN)', ...]

// Facade shorthand:
PersianTools::detectMobileOperator('09123456789');
```

### Detect Province from Phone Number

```php
use FallahAlireza\PersianTools\Detectors\PhoneAreaCodeDetector;

PhoneAreaCodeDetector::fromAreaCode('021');
// → 'تهران'

PhoneAreaCodeDetector::fromPhoneNumber('02112345678');
// → 'تهران'

PhoneAreaCodeDetector::allProvinces();
// → ['مازندران', 'گیلان', 'تهران', ...]

// Facade shorthand:
PersianTools::detectPhoneProvince('021');
PersianTools::detectPhoneProvince('02112345678');
```

---

## ✅ Validation Rules

### String-based (when `register_rules = true`)

```php
$rules = [
    'name'        => 'required|persian_alpha',
    'mobile'      => 'required|ir_mobile',
    'national_id' => 'required|ir_national_id',
    'birth_date'  => 'required|persian_date',
    'card'        => 'required|ir_bank_card',
    'plate'       => 'required|ir_license_plate',
    'eco_code'    => 'required|ir_economic_code',
];
```

### Class-based

```php
use FallahAlireza\PersianTools\Rules\IranianBankCardNumber;
use FallahAlireza\PersianTools\Rules\IranianMobile;
use FallahAlireza\PersianTools\Enums\MobileFormat;

$rules = [
    'mobile' => ['required', new IranianMobile(format: MobileFormat::Zero)],
    'card'   => ['required', new IranianBankCardNumber(separator: '-')],
];
```

### Type-safe Enum

```php
use FallahAlireza\PersianTools\Enums\PersianRule;

$rules = [
    'name'   => ['required', PersianRule::PersianAlpha->value],
    'mobile' => ['required', PersianRule::IranianMobile->value],
];
```

---

## 📋 All Validation Rules

### 🔤 Persian Text & Numbers

| Rule | Description | Valid | Invalid |
|---|---|---|---|
| `persian_alpha` | حروف فارسی، علائم و فاصله | سلام، علی‌رضا | Hello |
| `persian_alpha_num` | حروف + اعداد فارسی | سلام۱۲۳ | Hello 123 |
| `persian_alpha_eng_num` | حروف فارسی + اعداد فارسی/انگلیسی | سلام123 | Hello |
| `persian_num` | فقط اعداد فارسی | ۱۲۳۴۵ | 12345 |
| `persian_not_accept` | رد کردن هر چیز فارسی | Hello 123 | سلام |

### 📅 Persian Dates

| Rule | Parameters | Description |
|---|---|---|
| `persian_date` | `separator`, `convertPersianNumbers` | تاریخ شمسی معتبر |
| `persian_date_between` | `startDate`, `endDate`, `separator`, `convertPersianNumbers` | تاریخ بین دو تاریخ |
| `persian_month` | — | نام ماه شمسی |
| `persian_day` | — | نام روز هفته |

### 📱 Phone Numbers

| Rule | Parameters | Valid |
|---|---|---|
| `ir_mobile` | `format`, `convertPersianNumbers` | 09123456789, +98912... |
| `ir_phone` | `withAreaCode`, `areaCodeSeparator`, `withCountryCodeFormat`, `convertPersianNumbers` | 02112345678 |
| `ir_phone_area_code` | `convertPersianNumbers` | 021, 031 |

### 🪪 Identifiers

| Rule | Description | Valid |
|---|---|---|
| `ir_national_id` | کد ملی (با checksum) | 0013542419 |
| `ir_company_id` | شناسه ملی اشخاص حقوقی | 14007650912 |
| `ir_economic_code` | کد اقتصادی ۱۴ رقمی | 14004800101010 |

### 🏦 Banking

| Rule | Description |
|---|---|
| `ir_bank_card` | کارت بانکی (Luhn + BIN detection) |
| `ir_iban` | شماره شبا (IBAN checksum) |
| `ir_bank_account` | شماره حساب بانکی |

### 📍 Other

| Rule | Parameters | Description |
|---|---|---|
| `ir_postal_code` | `separator` | کد پستی ۱۰ رقمی |
| `ir_license_plate` | `allowMotorcycle` | پلاک خودرو |

---

## ➕ Adding New Data

All static data lives in `src/Data/`. To extend:

**Add a new bank BIN:**
```php
// src/Data/BankBins.php
'639607' => 'بانک جدید',
```

**Add a new mobile prefix:**
```php
// src/Data/MobileOperators.php
'اپراتور جدید' => ['995', '996'],
```

**Add a new area code:**
```php
// src/Data/PhoneAreaCodes.php
'استان جدید' => ['029'],
```

---

## ⚙️ Configuration

```php
// config/persian-tools.php
return [
    'register_rules'         => true,   // Register rules as Laravel validator strings
    'accept_persian_numbers' => false,  // Accept Persian digits globally
];
```

---

## 🌐 Localization

Set your app locale in `config/app.php`:

```php
'locale' => 'fa',
```

---

## 🧪 Testing

```bash
composer test
```

---

## 📄 License

MIT License — [Alireza Fallah](https://github.com/fallahalireza)
