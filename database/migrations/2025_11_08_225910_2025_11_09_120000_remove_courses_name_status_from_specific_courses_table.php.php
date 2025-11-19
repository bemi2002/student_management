<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specific_courses', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('specific_courses', 'courses_name')) {
                $table->dropColumn('courses_name');
            }
            if (Schema::hasColumn('specific_courses', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('specific_courses', function (Blueprint $table) {
            // Recreate columns in case of rollback
            $table->string('courses_name')->after('specific_courses_code');
            $table->enum('status', [
                'draft', 
                'upcoming', 
                'open_for_enrollment', 
                'enrollment_closed', 
                'ongoing', 
                'completed', 
                'cancelled', 
                'postponed'
            ])->default('draft')->after('courses_fee');
        });
    }
};
