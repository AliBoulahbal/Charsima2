<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('deliveries', function (Blueprint $table) {
        // On vérifie si les colonnes n'existent pas déjà pour éviter les plantages
        if (!Schema::hasColumn('deliveries', 'paid_amount')) {
            $table->decimal('paid_amount', 15, 2)->default(0)->after('final_price');
        }
        if (!Schema::hasColumn('deliveries', 'remaining_amount')) {
            $table->decimal('remaining_amount', 15, 2)->default(0)->after('paid_amount');
        }
        if (!Schema::hasColumn('deliveries', 'payment_status')) {
            $table->string('payment_status')->default('unpaid')->after('remaining_amount');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            //
        });
    }
};
