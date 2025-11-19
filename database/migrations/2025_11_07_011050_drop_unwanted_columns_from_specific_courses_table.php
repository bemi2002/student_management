<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specific_courses', function (Blueprint $table) {
            // Drop the foreign key only if it exists
            if (Schema::hasColumn('specific_courses', 'location_id')) {
                // Safely drop foreign key if exists
                try {
                    DB::statement('ALTER TABLE specific_courses DROP FOREIGN KEY specific_courses_location_id_foreign');
                } catch (\Throwable $e) {
                    // Ignore if FK doesn’t exist
                }

                $table->dropColumn('location_id');
            }

            foreach (['schedule', 'courses_fee', 'status', 'start_date', 'end_date'] as $col) {
                if (Schema::hasColumn('specific_courses', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('specific_courses', function (Blueprint $table) {
            // Re-add columns if rolled back
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('schedule')->nullable();
            $table->decimal('courses_fee', 10, 2)->nullable();
            $table->enum('status', [
                'draft',
                'upcoming',
                'open_for_enrollment',
                'enrollment_closed',
                'ongoing',
                'completed',
                'cancelled',
                'postponed'
            ])->default('draft');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }
};

