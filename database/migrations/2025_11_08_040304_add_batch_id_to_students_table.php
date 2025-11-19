<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Temporarily allow NULL to add the column
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'batch_id')) {
                $table->foreignId('batch_id')->nullable()->after('enrollment_id')->constrained('batches')->onDelete('cascade');
            }
        });

        // 2. Assign a default batch_id for all existing students
        $defaultBatchId = DB::table('batches')->value('id'); // pick the first batch
        if ($defaultBatchId) {
            DB::table('students')->whereNull('batch_id')->update(['batch_id' => $defaultBatchId]);
        } else {
            throw new Exception('No batches exist. Create at least one batch before running this migration.');
        }

        // 3. Make batch_id NOT NULL
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('batch_id')->nullable(false)->change();
        });

        // 4. Remove old columns if they exist
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('students', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (Schema::hasColumn('students', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Restore old columns
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable();
            }

            // Drop batch_id foreign key and column
            if (Schema::hasColumn('students', 'batch_id')) {
                $table->dropForeign(['batch_id']);
                $table->dropColumn('batch_id');
            }
        });
    }
};
