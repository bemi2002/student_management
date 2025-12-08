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
        Schema::table('company_address', function (Blueprint $table) {
            $table->dropColumn(['email', 'contact_phone', 'website']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_address', function (Blueprint $table) {
            $table->string('email')->after('company_name');
            $table->string('contact_phone')->after('email');
            $table->string('website')->after('building_number');
        });
    }
};