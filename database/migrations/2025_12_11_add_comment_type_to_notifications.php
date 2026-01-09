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
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT notifications_type_check");
        
        // Add the new check constraint with 'comment' type included
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('like', 'friend_request', 'message', 'connect', 'comment'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original constraint
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT notifications_type_check");
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('like', 'friend_request', 'message', 'connect'))");
    }
};
