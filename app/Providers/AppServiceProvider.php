<?php

namespace App\Providers;

// ➡️ CORRECTION : L'instruction 'use' pour la classe parente est essentielle.
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Cette méthode est utilisée pour enregistrer les services (binding)
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cette méthode est utilisée pour initialiser les services (routes, vues, etc.)
    }
}