<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the existing table if it has wrong structure
        Schema::dropIfExists('enrollment_dropouts');
        
        // Create the table with correct structure
        Schema::create('enrollment_dropouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->constrained('enrollmentss')->onDelete('cascade');
            $table->text('reason');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate dropout records
            $table->unique(['student_id', 'enrollment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollment_dropouts');
    }
};