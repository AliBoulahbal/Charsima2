<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('district');   // حي / عنوان
            $table->string('phone')->nullable();
            $table->string('manager_name'); // مدير المؤسسة
            $table->integer('student_count')->default(0);
            $table->string('wilaya');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
};
