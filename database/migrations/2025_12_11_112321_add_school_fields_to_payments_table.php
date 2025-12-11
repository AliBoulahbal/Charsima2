<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Nouveaux champs pour associer paiement à école/kiosque
            $table->foreignId('kiosk_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
            $table->string('school_name')->nullable();
            $table->string('wilaya')->nullable()->index();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Index
            $table->index(['school_id', 'payment_date']);
            $table->index(['kiosk_id', 'payment_date']);
            $table->index(['wilaya', 'payment_date']);
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropForeign(['kiosk_id']);
            $table->dropForeign(['school_id']);
            $table->dropForeign(['confirmed_by']);
            
            $table->dropColumn([
                'kiosk_id',
                'school_id',
                'school_name',
                'wilaya',
                'reference_number',
                'notes',
                'confirmed_by'
            ]);
            
            // Supprimer les index
            $table->dropIndex(['wilaya']);
            $table->dropIndex(['school_id_payment_date']);
            $table->dropIndex(['kiosk_id_payment_date']);
            $table->dropIndex(['wilaya_payment_date']);
        });
    }
};