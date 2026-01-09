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
        Schema::table('messages', function (Blueprint $table) {
            // Add a JSON column to track which users have deleted this message
            if (!Schema::hasColumn('messages', 'deleted_for')) {
                $table->json('deleted_for')->nullable()->after('is_read');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'deleted_for')) {
                $table->dropColumn('deleted_for');
            }
        });
    }
};
