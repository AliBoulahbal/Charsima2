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
        Schema::table('schools', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('wilaya');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('radius', 5, 3)->nullable()->after('longitude')
                  ->comment('Rayon de validation en km (défaut: 0.05 = 50m)');
            
            // Index pour les recherches géospatiales
            $table->index(['latitude', 'longitude']);
            $table->index('wilaya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius']);
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['wilaya']);
        });
    }
};