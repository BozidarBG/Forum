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
    ],

    'facebook' => [
        'client_id' => '531722847271588',
        'client_secret' => '71e483c5bd08d81f34a481c9e2af8673',
        'redirect' => 'https://forummoj.test/login/facebook/callback',
    ],

    'github' => [
        'client_id' => env('3725c18db74dbfc9ca58'),         // Your GitHub Client ID
        'client_secret' => env('b530c1eaa8c2b22fb030ca0140068ec2edc95f1a'), // Your GitHub Client Secret
        'redirect' => 'http://forummoj.test/github/redirect',
    ],
    'google' => [

        'client_id' => env('GOOGLE_CLIENT_ID'),

        'client_secret' => env('GOOGLE_CLIENT_SECRET'),

        'redirect' => env('GOOGLE_CALLBACK_URL'),

    ],

];
