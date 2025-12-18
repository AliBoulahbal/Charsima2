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
    Schema::create('card_allocations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
        $table->foreignId('card_type_id')->nullable()->constrained()->onDelete('set null');
        $table->integer('quantity')->default(0);
        $table->integer('quantity_used')->default(0);
        $table->date('allocation_date');
        $table->date('expiry_date')->nullable();
        $table->string('status')->default('active'); // active, expired, cancelled
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_allocations');
    }
};
