<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    'facebook' => [
        'client_id' => "1850071101758049",
        'client_secret' => "cebca96cd09aaa2f08bcb925a5b5b943",
        'redirect' => 'https://byxmart.store/auth/facebook/callback',
    ],
    'google' => [
        'client_id' => "629931872147-jlt1eqo5oj7r5nkpuh298jd80stbplqn.apps.googleusercontent.com",
        'client_secret' => "jW3m3hedP04mwt5A4SS_0PpS",
        'redirect' => 'https://byxmart.store/auth/google/callback',
    ],
    'github' => [
        'client_id' => "db15bdc9f69c6883bcd4",
        'client_secret' => "fadee7515b19c32773e0c731af2a58d19d000ce7",
        'redirect' => 'http://byxmart.store/auth/github/callback',
    ],


];
