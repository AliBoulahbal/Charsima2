<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('payments', function (Blueprint $table) {
        if (!Schema::hasColumn('payments', 'amount')) {
            $table->decimal('amount', 12, 2)->nullable();
        }
        
        if (!Schema::hasColumn('payments', 'note')) {
            // On retire le ->after('payment_method') pour éviter l'erreur SQL
            $table->text('note')->nullable(); 
        }
    });
}

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revenir en arrière si nécessaire
            $table->dropColumn(['amount', 'note']);
        });
    }
};