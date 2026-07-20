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
            $table->text('outbound_trips_json')->nullable()->after('outbound_dropoff_location');
            $table->text('return_trips_json')->nullable()->after('return_dropoff_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training', function (Blueprint $table) {
            $table->dropColumn(['outbound_trips_json', 'return_trips_json']);
        });
    }
};
