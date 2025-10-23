<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Cho phép tất cả API + sanctum

    'allowed_methods' => ['*'], // Cho phép tất cả phương thức: GET, POST, PUT, DELETE

    'allowed_origins' => ['*'], // Cho phép mọi domain (localhost, vercel, render, v.v.)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Cho phép mọi header

    'exposed_headers' => [], // Đặt đúng tên key (không bị lỗi chính tả)

    'max_age' => 0,

    'supports_credentials' => true, // Cho phép gửi cookie/token giữa các domain
];
