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
        Schema::table('courses_type', function (Blueprint $table) {
            $table->renameColumn('capacity', 'student_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses_type', function (Blueprint $table) {
            $table->renameColumn('student_capacity', 'capacity');
        });
    }
};
