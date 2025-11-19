<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
           
            $table->string('student_id')->unique();
            $table->string('enrollment_id')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact_phone')->nullable();
            $table->date('registration_date');
            $table->string('interested_course')->nullable();
            $table->enum('heard_about_us', ['friend', 'social_media', 'website', 'advertisement', 'other'])->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated', 'completed'])->default('active');
            $table->date('termination_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};