<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('teachers', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
            if (Schema::hasColumn('teachers', 'course_taught')) {
                $table->dropColumn('course_taught');
            }

            // Add new column
            $table->text('areas_of_expertise')->nullable()->after('specialization');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Recreate dropped columns
            $table->string('contact_phone')->nullable()->after('specialization');
            $table->string('course_taught')->nullable()->after('contact_phone');

            // Drop the new column
            $table->dropColumn('areas_of_expertise');
        });
    }
};
