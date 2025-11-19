<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Remove course_code if it exists
            if (Schema::hasColumn('courses', 'course_code')) {
                $table->dropColumn('course_code');
            }

            // Add end_date as nullable to avoid issues with existing rows
            if (!Schema::hasColumn('courses', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Re-add course_code if missing
            if (!Schema::hasColumn('courses', 'course_code')) {
                $table->string('course_code')->unique()->after('id');
            }

            // Drop end_date if exists
            if (Schema::hasColumn('courses', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};
