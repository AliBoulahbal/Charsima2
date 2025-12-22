<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', 'user'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => ['*'],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => [
        'Content-Type',
        'X-Auth-Token',
        'Origin',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'Accept',
        'Device-Name',
        'X-XSRF-TOKEN',
    ],
    
    'exposed_headers' => [
        'Authorization',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];