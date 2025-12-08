<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if the table exists and add the missing column
        if (Schema::hasTable('rejected_enrollments')) {
            if (!Schema::hasColumn('rejected_enrollments', 'enrollmentss_id')) {
                Schema::table('rejected_enrollments', function (Blueprint $table) {
                    $table->foreignId('enrollmentss_id')->constrained('enrollmentss')->onDelete('cascade');
                });
            }
        } else {
            // Create the table if it doesn't exist
            Schema::create('rejected_enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('enrollmentss_id')->constrained('enrollmentss')->onDelete('cascade');
                $table->text('reason');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('rejected_enrollments');
    }
};