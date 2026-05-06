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
        Schema::table('training', function (Blueprint $table) {
            $table->boolean('need_transportation')->default(false);
            $table->time('outbound_pickup_time')->nullable();
            $table->string('outbound_contact_number')->nullable();
            $table->string('outbound_pickup_location')->nullable();
            $table->string('outbound_dropoff_location')->nullable();
            $table->boolean('return_trip_needed')->default(false);
            $table->time('return_pickup_time')->nullable();
            $table->string('return_contact_number')->nullable();
            $table->string('return_pickup_location')->nullable();
            $table->string('return_dropoff_location')->nullable();
            $table->boolean('notify_coordinator')->default(false);
            $table->foreignId('coordinator_to_notify')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coordinator_to_notify');
            $table->dropColumn([
                'need_transportation',
                'outbound_pickup_time',
                'outbound_contact_number',
                'outbound_pickup_location',
                'outbound_dropoff_location',
                'return_trip_needed',
                'return_pickup_time',
                'return_contact_number',
                'return_pickup_location',
                'return_dropoff_location',
                'notify_coordinator',
            ]);
        });
    }
};
