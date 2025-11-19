<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
           
            $table->string('course_code')->unique();
            $table->string('course_name');
            $table->text('description');
            $table->integer('duration_dates');
            $table->decimal('price', 10, 2);
            $table->string('level');
            $table->date('start_date');
             

            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};