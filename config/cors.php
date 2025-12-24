<?php

// config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['*'], // Autorise POST, GET, OPTIONS, etc.

    // Remplacez par '*' pour le développement ou l'URL précise de Flutter
    'allowed_origins' => ['*'], 

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Très important pour Authorization et Content-Type

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];