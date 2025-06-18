<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may be made
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    'paths' => ['api/*', 'voucher/public/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://mylocalweb.test:8000'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];