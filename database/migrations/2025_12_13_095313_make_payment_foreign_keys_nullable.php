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
            // 1. Supprimer l'ancienne contrainte (si elle existe)
            $table->dropForeign(['distributor_id']); 
            
            // 2. Rendre la colonne nullable et modifier
            $table->foreignId('distributor_id')->nullable()->change();
            
            // 3. Recréer la contrainte
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('set null');

            // Par sécurité, si kiosk_id n'est pas déjà nullable :
            // $table->dropForeign(['kiosk_id']); 
            // $table->foreignId('kiosk_id')->nullable()->change();
            // $table->foreign('kiosk_id')->references('id')->on('kiosks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remettre la contrainte NOT NULL (si nécessaire, attention aux données existantes)
            $table->dropForeign(['distributor_id']);
            $table->foreignId('distributor_id')->nullable(false)->change();
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('cascade');
        });
    }
};