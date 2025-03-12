<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('training', 'is_updated')) {
            Schema::table('training', function (Blueprint $table) {
                $table->boolean('is_updated')->default(false)->after('location'); // Adjust position if needed
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('training', 'is_updated')) {
            Schema::table('training', function (Blueprint $table) {
                $table->dropColumn('is_updated');
            });
        }
    }
};
