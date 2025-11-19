<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fill end_date for existing courses based on start_date + duration_dates
        DB::table('courses')->whereNull('end_date')->get()->each(function ($course) {
            $endDate = date('Y-m-d', strtotime($course->start_date . " +{$course->duration_dates} days"));
            DB::table('courses')->where('id', $course->id)->update(['end_date' => $endDate]);
        });

        // Make end_date column NOT NULL
        Schema::table('courses', function (Blueprint $table) {
            $table->date('end_date')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->date('end_date')->nullable()->change();
        });
    }
};
