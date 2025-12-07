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
        if (!Schema::hasTable('friend_requests')) {
            Schema::create('friend_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // who sent the request
                $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // who receives the request
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->timestamps();

                $table->unique(['sender_id', 'receiver_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('friend_requests');
    }
};
