<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    */

    'name' => env('COMPANY_NAME', 'หจก. เกษมสุขวัฒนกิจ'),
    'legal_name' => env('COMPANY_LEGAL_NAME', 'ห้างหุ้นส่วนจำกัด เกษมสุขวัฒนกิจ'),
    'name_en' => env('COMPANY_NAME_EN', 'KASEMSUK WATTHANAKIT LIMITED PARTNERSHIP'),
    'tagline' => env('COMPANY_TAGLINE', 'รับทำบัญชีและยื่นภาษี'),
    'service_scope' => env('COMPANY_SERVICE_SCOPE', 'บริการให้คำปรึกษาด้านบัญชีและภาษีอากร / รับทำบัญชี'),
    'tax_id' => env('COMPANY_TAX_ID', '0133565001835'),
    'business' => env('COMPANY_BUSINESS', 'กิจกรรมเกี่ยวกับบัญชีการทำบัญชีและการตรวจสอบบัญชี การให้คำปรึกษาด้านภาษี'),
    'status' => env('COMPANY_STATUS', 'ยังดำเนินกิจการอยู่'),
    'registered_on' => env('COMPANY_REGISTERED_ON', '24 มีนาคม 2565'),
    'registered_capital' => env('COMPANY_REGISTERED_CAPITAL', '100,000 บาท'),

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */

    'contact_name' => env('COMPANY_CONTACT_NAME', 'นางสาวมณีพร สิงหนุวงศา'),
    'contact_position' => env('COMPANY_CONTACT_POSITION', 'Chief Executive Officer (CEO)'),
    'phone' => env('COMPANY_PHONE', '+66994951663'),
    'phone_display' => env('COMPANY_PHONE_DISPLAY', '099-495-1663'),
    'email' => env('COMPANY_EMAIL', 'maneepornson2731@gmail.com'),
    'line_id' => env('COMPANY_LINE_ID', 'khunblue.kss'),
    'line_url' => env('COMPANY_LINE_URL', 'https://lin.ee/zhk8DNs'),

    /*
    |--------------------------------------------------------------------------
    | Address
    |--------------------------------------------------------------------------
    */

    'address' => env('COMPANY_ADDRESS', '54/161 หมู่บ้านศุภาลัยไพร์ด วงแหวน-ลำลูกกา คลอง 6 หมู่ที่ 7 ตำบลบึงคำพร้อย อำเภอลำลูกกา จ.ปทุมธานี 12150'),
    'address_lines' => array_values(array_filter([
        env('COMPANY_ADDRESS_LINE_1', '54/161 หมู่บ้านศุภาลัยไพร์ด วงแหวน-ลำลูกกา คลอง 6'),
        env('COMPANY_ADDRESS_LINE_2', 'หมู่ที่ 7 ตำบลบึงคำพร้อย อำเภอลำลูกกา'),
        env('COMPANY_ADDRESS_LINE_3', 'จ.ปทุมธานี 12150'),
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
