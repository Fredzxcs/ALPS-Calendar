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
            // Drop the foreign key constraint if it exists
            if (Schema::hasColumn('training', 'coordinator_to_notify')) {
                try {
                    $table->dropForeign(['coordinator_to_notify']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, that's okay
                }
                // Change the column from unsignedBigInteger to text to support comma-separated IDs
                $table->text('coordinator_to_notify')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training', function (Blueprint $table) {
            // Revert back to an unsigned big integer column
            if (Schema::hasColumn('training', 'coordinator_to_notify')) {
                $table->unsignedBigInteger('coordinator_to_notify')->nullable()->change();
                // Restore the foreign key constraint
                $table->foreign('coordinator_to_notify')->references('id')->on('users')->nullOnDelete();
            }
        });
    }
};
