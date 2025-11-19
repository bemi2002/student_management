<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add company_id if not exists
            if (!Schema::hasColumn('teachers', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('specialization');
                $table->foreign('company_id')
                      ->references('id')
                      ->on('companies')
                      ->onDelete('set null');
            }

            // Add areas_of_expertise if not exists
            if (!Schema::hasColumn('teachers', 'areas_of_expertise')) {
                $table->text('areas_of_expertise')->nullable()->after('company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop the added columns safely
            if (Schema::hasColumn('teachers', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
            if (Schema::hasColumn('teachers', 'areas_of_expertise')) {
                $table->dropColumn('areas_of_expertise');
            }
        });
    }
};
