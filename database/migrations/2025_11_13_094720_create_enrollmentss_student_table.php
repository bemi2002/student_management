<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollmentss_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollmentss')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['enrollment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollmentss_student');
    }
};