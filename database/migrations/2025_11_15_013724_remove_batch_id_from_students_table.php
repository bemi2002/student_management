<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Get list of all foreign keys
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'students' 
                  AND COLUMN_NAME = 'batch_id'
                  AND CONSTRAINT_SCHEMA = DATABASE();
            ");

            // Drop FK if exists
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }

            // Drop column if exists
            if (Schema::hasColumn('students', 'batch_id')) {
                $table->dropColumn('batch_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Restore column
            if (!Schema::hasColumn('students', 'batch_id')) {
                $table->foreignId('batch_id')
                    ->nullable()
                    ->constrained('batches')
                    ->onDelete('cascade');
            }
        });
    }
};
