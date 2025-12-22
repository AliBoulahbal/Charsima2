<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Utilisation de Blueprint $table (et non User $table)
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

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'remaining_amount', 'payment_status']);
        });
    }
};