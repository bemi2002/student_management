<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $schema = DB::getSchemaBuilder();

            if ($schema->hasColumn('company_addresses', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if ($schema->hasColumn('company_addresses', 'email')) {
                $table->dropColumn('email');
            }
            if ($schema->hasColumn('company_addresses', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
            if ($schema->hasColumn('company_addresses', 'website')) {
                $table->dropColumn('website');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('company_addresses', 'company_name')) {
                $table->string('company_name')->nullable();
            }
            if (!Schema::hasColumn('company_addresses', 'email')) {
                $table->string('email')->nullable()->unique();
            }
            if (!Schema::hasColumn('company_addresses', 'contact_phone')) {
                $table->string('contact_phone')->nullable();
            }
            if (!Schema::hasColumn('company_addresses', 'website')) {
                $table->string('website')->nullable();
            }
        });
    }
};
