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
        Schema::table('notifications', function (Blueprint $table) {
            // Add nullable post_id to track which post the notification is about
            if (!Schema::hasColumn('notifications', 'post_id')) {
                $table->foreignId('post_id')->nullable()->constrained('posts')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'post_id')) {
                $table->dropForeign(['post_id']);
                $table->dropColumn('post_id');
            }
        });
    }
};
