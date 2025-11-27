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
        Schema::create('site_logs', function (Blueprint $table) {
            $table->id();
             $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->boolean('is_up');
            $table->integer('response_time')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamp('checked_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_logs');
    }
};
