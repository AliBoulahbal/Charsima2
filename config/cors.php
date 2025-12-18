<?php 

return [
    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Ajoutez ici toutes les origines qui seront autorisées à faire des requêtes
    | vers votre API.
    |
    */
    'allowed_origins' => [
        '*', // L'option la plus simple pour le développement local
        'http://localhost',
        'http://127.0.0.1',
        'http://10.0.2.2', // Adresse de l'émulateur Android
        'http://[::1]',    // Adresse IPv6 locale
    ],
    
    // ... assurez-vous que toutes les autres options sont larges pour le développement
    
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_origins' => ['*'], // Pour développement seulement
    'allowed_origins_patterns' => [],
    'supports_credentials' => true,


];