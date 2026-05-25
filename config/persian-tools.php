<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Register Validation Rules
    |--------------------------------------------------------------------------
    | When enabled, all rules are registered with Laravel's validator so you
    | can use them as strings: 'field' => 'required|persian_alpha'
    |
    */
    'register_rules' => true,

    /*
    |--------------------------------------------------------------------------
    | Accept Persian Numbers Globally
    |--------------------------------------------------------------------------
    | When enabled, rules that involve numbers will automatically accept and
    | convert Persian digits (۰-۹) to English digits globally.
    | Per-rule `convertPersianNumbers` param takes precedence over this setting.
    |
    */
    'accept_persian_numbers' => false,
];
