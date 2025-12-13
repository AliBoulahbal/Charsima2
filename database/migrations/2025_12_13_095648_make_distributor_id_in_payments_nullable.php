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
            // Rendre distributor_id nullable
            
            // 1. Supprimer l'ancienne contrainte de clé étrangère si elle existe (le nom par défaut est souvent 'table_colonne_foreign')
            if (Schema::hasColumn('payments', 'distributor_id')) {
                // Laravel génère un nom de contrainte comme 'payments_distributor_id_foreign'
                $table->dropForeign(['distributor_id']); 
            }
            
            // 2. Rendre la colonne nullable et modifier
            $table->foreignId('distributor_id')->nullable()->change();
            
            // 3. Recréer la contrainte avec onDelete('set null') pour les lignes existantes
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('set null');

            // Répéter pour kiosk_id et school_id si vous prévoyez des paiements sans l'un ou l'autre (par sécurité)
            // if (Schema::hasColumn('payments', 'kiosk_id')) {
            //     $table->dropForeign(['kiosk_id']);
            //     $table->foreignId('kiosk_id')->nullable()->change();
            //     $table->foreign('kiosk_id')->references('id')->on('kiosks')->onDelete('set null');
            // }
            // if (Schema::hasColumn('payments', 'school_id')) {
            //     $table->dropForeign(['school_id']);
            //     $table->foreignId('school_id')->nullable()->change();
            //     $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revenir à la version NOT NULL (Attention aux données null existantes)
            $table->dropForeign(['distributor_id']);
            $table->foreignId('distributor_id')->nullable(false)->change();
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('cascade');
        });
    }
};