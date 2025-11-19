<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop foreign key only if it exists
            try {
                DB::statement('ALTER TABLE teachers DROP FOREIGN KEY teachers_company_id_foreign');
            } catch (\Throwable $e) {
                // Ignore if the key doesn't exist
            }

            // Drop columns safely
            if (Schema::hasColumn('teachers', 'company_id')) {
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('teachers', 'areas_of_expertise')) {
                $table->dropColumn('areas_of_expertise');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('specialization');
                $table->foreign('company_id')
                      ->references('id')
                      ->on('companies')
                      ->onDelete('set null');
            }

            if (!Schema::hasColumn('teachers', 'areas_of_expertise')) {
                $table->text('areas_of_expertise')->nullable()->after('company_id');
            }
        });
    }
};
