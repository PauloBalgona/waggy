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
            // Add indexes for better query performance
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['receiver_id', 'is_read']);
            $table->index('created_at');
            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['sender_id', 'receiver_id']);
            $table->dropIndex(['receiver_id', 'is_read']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['conversation_id']);
        });
    }
};
