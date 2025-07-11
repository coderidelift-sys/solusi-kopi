<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi Midtrans payment gateway
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    /*
    |--------------------------------------------------------------------------
    | Midtrans API URLs
    |--------------------------------------------------------------------------
    */

    'api_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://api.midtrans.com'
        : 'https://api.sandbox.midtrans.com',

    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    /*
    |--------------------------------------------------------------------------
    | Notification URL
    |--------------------------------------------------------------------------
    */

    'notification_url' => env('APP_URL') . '/api/midtrans/notification',

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    */

    'enabled_payments' => [
        'credit_card',
        'bca_va',
        'bni_va',
        'bri_va',
        'gopay',
        'indomaret',
        'danamon_online',
        'akulaku',
        'shopeepay',
        'qris'
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    */

    'finish_url' => env('APP_URL') . '/order/success',
    'error_url' => env('APP_URL') . '/order/payment/error',
    'pending_url' => env('APP_URL') . '/order/payment/pending',
    'unfinish_url' => env('APP_URL') . '/order/payment/unfinish',
];
