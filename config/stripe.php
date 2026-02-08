<?php

return [
    'STRIPE_PUBLIC_KEY' => env('STRIPE_PUBLIC_KEY', ''),
    'STRIPE_API_KEY' => env('STRIPE_API_KEY', ''),
    'STRIPE_API_VERSION' => env('STRIPE_API_VERSION', '2020-03-02'),
    'STRIPE_VERIFY_SSL_CERTS' => env('STRIPE_VERIFY_SSL_CERTS', false),
    'STRIPE_APP_NAME' => env('APP_NAME', '')
];