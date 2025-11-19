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
        Schema::table('enrollmentss', function (Blueprint $table) {
            // Add your new enrollment column here
            // For example, if you want to add an 'enrollment_date' column:
            $table->date('enrollment_date')->nullable()->after('course_id');
            
            // Or if you want to add an 'enrollment_status' column:
            // $table->enum('enrollment_status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Or any other column you need
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollmentss', function (Blueprint $table) {
            // Remove the column in rollback
            $table->dropColumn('enrollment_date');
            // $table->dropColumn('enrollment_status');
        });
    }
};