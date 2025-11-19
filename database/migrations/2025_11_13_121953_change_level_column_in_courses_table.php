<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the old column
            if (Schema::hasColumn('courses', 'level')) {
                $table->dropColumn('level');
            }

            // Add the new enum column
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the enum column if rolling back
            if (Schema::hasColumn('courses', 'level')) {
                $table->dropColumn('level');
            }

            // Optionally, recreate it as string (original column)
            $table->string('level')->after('price');
        });
    }
};
