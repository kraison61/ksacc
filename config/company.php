<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    */

    'name' => env('COMPANY_NAME', 'หจก. เกษมสุขวัฒนกิจ'),
    'name_en' => env('COMPANY_NAME_EN', 'Kasemsuk Wattanakij Ltd., Part.'),
    'tagline' => env('COMPANY_TAGLINE', 'รับทำบัญชีและยื่นภาษี'),
    'tax_id' => env('COMPANY_TAX_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */

    'phone' => env('COMPANY_PHONE', ''),
    'phone_display' => env('COMPANY_PHONE_DISPLAY', ''),
    'email' => env('COMPANY_EMAIL', ''),
    'line_id' => env('COMPANY_LINE_ID', ''),
    'line_url' => env('COMPANY_LINE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Address
    |--------------------------------------------------------------------------
    */

    'address' => env('COMPANY_ADDRESS', ''),
    'address_lines' => array_values(array_filter([
        env('COMPANY_ADDRESS_LINE_1'),
        env('COMPANY_ADDRESS_LINE_2'),
        env('COMPANY_ADDRESS_LINE_3'),
    ])),

    /*
    |--------------------------------------------------------------------------
    | Other
    |--------------------------------------------------------------------------
    */

    'office_hours' => env('COMPANY_OFFICE_HOURS', ''),
    'map_url' => env('COMPANY_MAP_URL', ''),
    'facebook_url' => env('COMPANY_FACEBOOK_URL', ''),

];
