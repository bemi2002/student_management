<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specific_courses', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');

            // Course identification
            $table->string('specific_courses_code')->unique();
            $table->string('courses_name'); 
            $table->text('description')->nullable();

            // Schedule and timing
            $table->date('start_date');
            $table->date('end_date');
            $table->string('schedule'); 
            $table->json('class_days')->nullable();

            // Fee
            $table->decimal('courses_fee', 10, 2);

            // Status and difficulty
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

            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');

            // Resources
            $table->text('resources')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specific_courses');
    }
};


