<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('training', function (Blueprint $table) {
            $table->date('outbound_pickup_date')->nullable();
            $table->date('return_pickup_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('training', function (Blueprint $table) {
            $table->dropColumn(['outbound_pickup_date', 'return_pickup_date']);
        });
    }
};
