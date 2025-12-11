<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kiosks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('wilaya');
            $table->string('district');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            // Index pour les recherches fréquentes
            $table->index('wilaya');
            $table->index('is_active');
            $table->index(['wilaya', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kiosks');
    }
};