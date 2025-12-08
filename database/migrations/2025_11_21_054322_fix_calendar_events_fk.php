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
        $table->dropForeign(['enrollmentss_id']);

        $table->foreign('enrollmentss_id')
              ->references('id')
              ->on('enrollmentss')
              ->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
        $table->dropForeign(['enrollmentss_id']);
    });
    }
};
