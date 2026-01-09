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
        // Drop the existing check constraint
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check");
        // Normalize any existing types to allowed set before enforcing the constraint.
        DB::statement("UPDATE notifications SET type = 'message' WHERE type IS NULL OR type NOT IN ('like', 'friend_request', 'message', 'connect', 'comment')");
        // Add the new check constraint with 'comment' type included
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('like', 'friend_request', 'message', 'connect', 'comment'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original constraint
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check");
        // Normalize any rows that would violate the older constraint (without 'comment')
        DB::statement("UPDATE notifications SET type = 'message' WHERE type IS NULL OR type NOT IN ('like', 'friend_request', 'message', 'connect')");
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('like', 'friend_request', 'message', 'connect'))");
    }
};
