<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_address_to_schools_table.php
public function up()
{
    Schema::table('schools', function (Blueprint $table) {
        $table->string('address')->nullable()->after('name');
    });
}

public function down()
{
    Schema::table('schools', function (Blueprint $table) {
        $table->dropColumn('address');
    });
}
};
