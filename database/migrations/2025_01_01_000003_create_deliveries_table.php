<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('distributor_id')->constrained()->onDelete('cascade');

            $table->date('delivery_date');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->integer('total_price');   // quantity * unit_price

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};
