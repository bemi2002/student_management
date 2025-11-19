<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Change availability_days to string and nullable
            $table->string('availability_days')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Revert back to previous type if needed
            $table->enum('availability_days', ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'])->nullable(false)->change();
        });
    }
};
