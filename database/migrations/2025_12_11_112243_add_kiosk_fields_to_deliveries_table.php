<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Nouveaux champs pour kiosques
            $table->foreignId('kiosk_id')->nullable()->constrained()->onDelete('set null');
            $table->string('delivery_type')->default('school')->index();
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->integer('final_price')->default(0);
            $table->string('status')->default('confirmed')->index();
            $table->string('transaction_id')->nullable()->unique();
            $table->string('payment_method')->default('cash');
            $table->string('teacher_name')->nullable();
            $table->string('teacher_phone')->nullable();
            $table->string('teacher_subject')->nullable();
            $table->string('teacher_email')->nullable();
            $table->string('customer_cin')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('wilaya')->nullable()->index();
            
            // Champs pour paiement hors ligne
            $table->string('online_payment_status')->nullable()->index();
            $table->string('payment_code')->nullable()->unique();
            $table->timestamp('payment_code_expires_at')->nullable();
            $table->timestamp('payment_confirmation_date')->nullable();
            $table->foreignId('payment_confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('payment_receipt_number')->nullable();
            $table->string('bank_deposit_slip')->nullable();
            $table->text('payment_verification_notes')->nullable();
            
            // Index pour recherche
            $table->index(['delivery_type', 'status']);
            $table->index(['payment_code', 'payment_code_expires_at']);
            $table->index(['kiosk_id', 'delivery_date']);
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Supprimer les nouvelles colonnes
            $table->dropForeign(['kiosk_id']);
            $table->dropForeign(['payment_confirmed_by']);
            
            $table->dropColumn([
                'kiosk_id',
                'delivery_type',
                'discount_percentage',
                'final_price',
                'status',
                'transaction_id',
                'payment_method',
                'teacher_name',
                'teacher_phone',
                'teacher_subject',
                'teacher_email',
                'customer_cin',
                'delivery_address',
                'notes',
                'wilaya',
                'online_payment_status',
                'payment_code',
                'payment_code_expires_at',
                'payment_confirmation_date',
                'payment_confirmed_by',
                'payment_receipt_number',
                'bank_deposit_slip',
                'payment_verification_notes'
            ]);
            
            // Supprimer les index
            $table->dropIndex(['delivery_type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['delivery_type_status']);
            $table->dropIndex(['payment_code_payment_code_expires_at']);
            $table->dropIndex(['kiosk_id_delivery_date']);
            $table->dropIndex(['wilaya']);
        });
    }
};