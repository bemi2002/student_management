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
    Schema::table('sub_courses', function (Blueprint $table) {
        $table->string('resource_type')->nullable()->after('name'); // 'url' or 'file'
        $table->string('resource_path')->nullable()->after('resource_type'); // file path or URL
    });
}

public function down(): void
{
    Schema::table('sub_courses', function (Blueprint $table) {
        $table->dropColumn(['resource_type', 'resource_path']);
    });
}

};
