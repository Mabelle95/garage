<?php

return [
    'public_key' => env('FEDAPAY_PUBLIC_KEY'),
    'secret_key' => env('FEDAPAY_SECRET_KEY'),
    'environment' => env('FEDAPAY_ENVIRONMENT', 'sandbox'),
    'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET'),

    // URLs de callback
    'callback_url' => env('APP_URL') . '/payment/callback',
    'success_url' => env('APP_URL') . '/payment/success',
    'cancel_url' => env('APP_URL') . '/payment/cancel',
];
