<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('conversation_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to not nullable, but since there may be null values, skip the change to avoid errors
        // Schema::table('messages', function (Blueprint $table) {
        //     $table->unsignedBigInteger('conversation_id')->nullable(false)->change();
        // });
    }
};
