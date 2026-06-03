<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Flutterwave Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Flutterwave API credentials for payment processing.
    | Supports Mobile Money (MTN, Airtel) and Card payments in Uganda.
    | Get your keys at: https://dashboard.flutterwave.com/settings/apis
    |
    */
    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY', ''),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY', ''),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY', ''),
        'secret_hash' => env('FLUTTERWAVE_SECRET_HASH', ''),
        'base_url' => env('FLUTTERWAVE_BASE_URL', 'https://api.flutterwave.com/v3'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Marz Wallet (MarzPay) Provider
    |--------------------------------------------------------------------------
    |
    | Configuration for Marz Wallet integration. Get credentials from
    | https://wallet.wearemarz.com/developer
    |
    */
    'marz' => [
        'base_url' => env('MARZ_BASE_URL', 'https://wallet.wearemarz.com/api/v1'),
        'api_key' => env('MARZ_API_KEY', ''),
        'public_key' => env('MARZ_PUBLIC_KEY', ''),
        'secret_key' => env('MARZ_SECRET_KEY', ''),
    ],

];
