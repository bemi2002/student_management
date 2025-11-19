<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();

            // approval status
            $table->enum('status', [
                'pending',
                'teacher_approved',
                'admin_approved',
                'completed'
            ])->default('pending');

            $table->timestamp('teacher_approved_at')->nullable();
            $table->foreignId('teacher_approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('admin_approved_at')->nullable();
            $table->foreignId('admin_approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('certificate_generated_at')->nullable();
            $table->string('certificate_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
