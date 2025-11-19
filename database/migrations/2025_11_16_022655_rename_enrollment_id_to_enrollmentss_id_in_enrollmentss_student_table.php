<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollmentss_student', function (Blueprint $table) {
            // Drop the old foreign key first
            $table->dropForeign(['enrollment_id']);

            // Rename the column
            $table->renameColumn('enrollment_id', 'enrollmentss_id');

            // Add new foreign key relation
            $table->foreign('enrollmentss_id')
                ->references('id')
                ->on('enrollmentss')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('enrollmentss_student', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['enrollmentss_id']);

            // Revert rename
            $table->renameColumn('enrollmentss_id', 'enrollment_id');

            // Restore the old FK
            $table->foreign('enrollment_id')
                ->references('id')
                ->on('enrollmentss')
                ->onDelete('cascade');
        });
    }
};
