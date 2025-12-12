<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ajoute la colonne delivery_id en tant que clé étrangère nullable
            $table->foreignId('delivery_id')
                  ->nullable()
                  ->after('school_id') // Position optionnelle, après school_id par exemple
                  ->constrained() // Crée la contrainte de clé étrangère vers la table 'deliveries'
                  ->onDelete('set null'); // Optionnel, mais recommandé si la livraison peut être supprimée
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['delivery_id']);
            
            // Supprimer la colonne
            $table->dropColumn('delivery_id');
        });
    }
};