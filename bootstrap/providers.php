<?php

return [
    // Fournisseur d'application de base (celui que vous aviez)
    App\Providers\AppServiceProvider::class,

    // ➡️ AJOUTS ESSENTIELS MANQUANTS :

    // Fournisseur d'authentification (celui qui cause l'erreur "Class not found")
    App\Providers\AuthServiceProvider::class,

    // Fournisseur d'événements
    App\Providers\EventServiceProvider::class,

    // Fournisseur de routes
    App\Providers\RouteServiceProvider::class,

    // Fournisseur de services Spatie (pour le middleware 'role')
    Spatie\Permission\PermissionServiceProvider::class,
];