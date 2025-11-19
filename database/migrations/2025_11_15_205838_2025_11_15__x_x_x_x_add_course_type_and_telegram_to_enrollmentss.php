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

            // Add course_type_id if it doesn't exist
            if (!Schema::hasColumn('enrollmentss', 'course_type_id')) {
                $table->foreignId('course_type_id')
                      ->nullable()
                      ->after('course_id')
                      ->constrained('courses_type')
                      ->onDelete('cascade');
            }

            // Add telegram_link if it doesn't exist
            if (!Schema::hasColumn('enrollmentss', 'telegram_link')) {
                $table->string('telegram_link')
                      ->nullable()
                      ->after('student_capacity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollmentss', function (Blueprint $table) {
            if (Schema::hasColumn('enrollmentss', 'telegram_link')) {
                $table->dropColumn('telegram_link');
            }

            if (Schema::hasColumn('enrollmentss', 'course_type_id')) {
                $table->dropForeign(['course_type_id']);
                $table->dropColumn('course_type_id');
            }
        });
    }
};
