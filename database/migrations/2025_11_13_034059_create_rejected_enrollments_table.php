<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rejected_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->constrained('enrollmentss')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->string('rejected_by')->nullable();
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['student_id', 'enrollment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rejected_enrollments');
    }
};