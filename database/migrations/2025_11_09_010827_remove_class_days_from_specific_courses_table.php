<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specific_courses', function (Blueprint $table) {
            $table->dropColumn('class_days');
        });
    }

    public function down(): void
    {
        Schema::table('specific_courses', function (Blueprint $table) {
            $table->json('class_days')->nullable(); // or string if previously string
        });
    }
};
