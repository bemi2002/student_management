<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Normalize existing data
        DB::table('courses_type')->get()->each(function ($row) {
            $validValues = ['morning', 'evening', 'weekend'];

            if (!in_array(strtolower($row->course_type), $validValues)) {
                // Set default to 'morning' if value is invalid
                DB::table('courses_type')
                    ->where('id', $row->id)
                    ->update(['course_type' => 'morning']);
            }
        });

        // Step 2: Alter column to ENUM
        Schema::table('courses_type', function (Blueprint $table) {
            $table->enum('course_type', ['morning', 'evening', 'weekend'])->change();
        });
    }

    public function down(): void
    {
        // Revert back to string
        Schema::table('courses_type', function (Blueprint $table) {
            $table->string('course_type')->change();
        });
    }
};
