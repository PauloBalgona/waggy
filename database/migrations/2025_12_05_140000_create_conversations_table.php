<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_one')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_two')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PostgreSQL requires dropping dependent foreign keys first
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS conversations CASCADE');
        } else {
            Schema::dropIfExists('conversations');
        }
    }
};
