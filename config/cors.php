<?php

return [

    'paths' => ['api/*'],
'allowed_origins' => ['*'], // hoặc ['https://your-frontend.vercel.app']
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Cho phép mọi header


    'max_age' => 0,

    'supports_credentials' => true, // Cho phép gửi cookie/token giữa các domain
];
