<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations (rend les colonnes nullable).
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // ATTENTION : Pour modifier une colonne de clé étrangère,
            // il faut souvent d'abord supprimer la contrainte.
            
            // 1. Supprimer l'ancienne clé étrangère (si nécessaire)
            // Assurez-vous d'utiliser le bon nom de clé si Laravel a utilisé un nom non standard.
            // Le nom par défaut est généralement 'table_colonne_foreign'.
            $table->dropForeign(['school_id']);
            $table->dropForeign(['distributor_id']);
            
            // 2. Rendre les colonnes nullable
            $table->foreignId('school_id')->nullable()->change();
            $table->foreignId('distributor_id')->nullable()->change();
            
            // 3. Recréer les contraintes de clé étrangère avec l'option nullable
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('cascade');
            
            // Si la colonne kiosk_id existe et n'est pas nullable, ajoutez-la ici:
            // $table->foreignId('kiosk_id')->nullable()->change(); 
            // $table->foreign('kiosk_id')->references('id')->on('kiosks')->onDelete('cascade');
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Supprimer et recréer les colonnes pour les remettre en NOT NULL
            $table->dropForeign(['school_id']);
            $table->foreignId('school_id')->nullable(false)->change();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            
            $table->dropForeign(['distributor_id']);
            $table->foreignId('distributor_id')->nullable(false)->change();
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('cascade');
        });
    }
};