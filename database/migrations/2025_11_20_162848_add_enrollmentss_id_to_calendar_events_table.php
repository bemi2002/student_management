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
        Schema::table('calendar_events', function (Blueprint $table) {
            // Add the enrollmentss_id column
            $table->foreignId('enrollmentss_id')
                  ->nullable()
                  ->constrained('enrollments')
                  ->onDelete('cascade')
                  ->after('end'); // You can change the position as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['enrollmentss_id']);
            $table->dropColumn('enrollmentss_id');
        });
    }
};