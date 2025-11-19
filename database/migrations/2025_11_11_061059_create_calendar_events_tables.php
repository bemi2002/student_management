<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // Event title
            $table->text('content')->nullable(); // Event description/content
            $table->timestamp('start');        // Start date & time
            $table->timestamp('end')->nullable(); // End date & time (optional)
            $table->timestamps();              // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
