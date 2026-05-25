<?php

namespace FallahAlireza\PersianTools\Enums;

enum PersianRule: string
{
    // Persian Text & Numbers
    case PersianAlpha = 'persian_alpha';
    case PersianAlphaNum = 'persian_alpha_num';
    case PersianAlphaEngNum = 'persian_alpha_eng_num';
    case PersianNum = 'persian_num';
    case PersianNotAccept = 'persian_not_accept';

    // Persian Date
    case PersianDate = 'persian_date';
    case PersianDateBetween = 'persian_date_between';
    case PersianMonth = 'persian_month';
    case PersianDay = 'persian_day';

    // Phone Numbers
    case IranianMobile = 'ir_mobile';
    case IranianPhone = 'ir_phone';
    case IranianPhoneAreaCode = 'ir_phone_area_code';

    // Identifiers
    case IranianNationalId = 'ir_national_id';
    case IranianCompanyId = 'ir_company_id';
    case IranianEconomicCode = 'ir_economic_code';

    // Banking
    case IranianBankCard = 'ir_bank_card';
    case IranianIban = 'ir_iban';
    case IranianBankAccount = 'ir_bank_account';

    // Other
    case IranianPostalCode = 'ir_postal_code';
    case IranianLicensePlate = 'ir_license_plate';
}
