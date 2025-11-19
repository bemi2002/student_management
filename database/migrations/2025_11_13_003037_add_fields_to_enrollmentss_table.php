<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('enrollmentss', function (Blueprint $table) {
        $table->string('title')->after('id');
        $table->text('description')->nullable()->after('title');
        $table->unsignedBigInteger('course_id')->after('description');
        $table->integer('capacity')->default(0)->after('course_id');
        $table->decimal('amount_to_be_paid', 10, 2)->default(0)->after('capacity');
        $table->enum('completion_status', ['Not Started', 'In Progress', 'Completed'])->default('Not Started')->after('amount_to_be_paid');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollmentss', function (Blueprint $table) {
            //
        });
    }
};
