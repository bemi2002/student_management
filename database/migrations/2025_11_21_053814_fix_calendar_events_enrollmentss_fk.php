<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {

            // 1. Drop wrong FK if exists
            try {
                $table->dropForeign(['enrollmentss_id']);
            } catch (\Exception $e) {
                // ignore if FK does not exist
            }

            // 2. Drop wrong column if exists
            if (Schema::hasColumn('calendar_events', 'enrollmentss_id')) {
                $table->dropColumn('enrollmentss_id');
            }

            // 3. Create correct column + correct foreign key
            $table->foreignId('enrollmentss_id')
                  ->nullable()
                  ->constrained('enrollmentss') // correct table name
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropForeign(['enrollmentss_id']);
            $table->dropColumn('enrollmentss_id');
        });
    }
};
