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
        // Update existing like notifications with post_id from post_likes table
        DB::statement("
            UPDATE notifications n
            SET post_id = (
                SELECT MAX(pl.post_id)
                FROM post_likes pl
                WHERE pl.user_id = n.actor_id
                AND pl.created_at >= n.created_at - INTERVAL '5 MINUTES'
                AND pl.created_at <= n.created_at + INTERVAL '5 MINUTES'
            )
            WHERE n.type = 'like' AND n.post_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - we're just updating data
    }
};
