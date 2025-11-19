<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'teacher_id')) {
                $table->dropColumn('teacher_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Re-add the column (adjust type if needed)
            $table->unsignedBigInteger('teacher_id')->nullable()->after('id');
        });
    }
};
