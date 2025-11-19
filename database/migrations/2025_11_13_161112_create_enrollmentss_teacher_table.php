<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollmentss_teacher', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('enrollmentss_id')
                  ->constrained('enrollmentss')
                  ->onDelete('cascade');

            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['enrollmentss_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollmentss_teacher');
    }
};
