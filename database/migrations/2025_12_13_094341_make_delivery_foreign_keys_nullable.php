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
        Schema::table('deliveries', function (Blueprint $table) {
            // 1. Supprimer l'ancienne contrainte de clé étrangère
            $table->dropForeign(['school_id']);
            $table->dropForeign(['distributor_id']); // Optionnel, mais recommandé

            // 2. Rendre les colonnes nullable
            $table->foreignId('school_id')->nullable()->change();
            $table->foreignId('distributor_id')->nullable()->change();
            
            // 3. Recréer les contraintes de clé étrangère avec l'option nullable
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Inverser uniquement si nécessaire, mais soyez prudent
            $table->dropForeign(['school_id']);
            $table->foreignId('school_id')->nullable(false)->change();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->dropForeign(['distributor_id']);
            $table->foreignId('distributor_id')->nullable(false)->change();
            $table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('cascade');
        });
    }
};