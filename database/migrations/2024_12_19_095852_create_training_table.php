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
        Schema::create('training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('course')->onDelete('cascade');
            $table->foreignId('facilitator_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('company')->onDelete('cascade');
            $table->string('assistant')->nullable();
            $table->string('platform')->nullable();
            $table->string('credentials_email')->nullable();
            $table->string('credentials_password')->nullable();
            $table->string('mode')->nullable();
            $table->string('location')->nullable();
            // $table->foreignId('credentials_id')->constrained('credentials')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training');
    }
};
