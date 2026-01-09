<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verify admin user certificate
        $admin = User::where('email', 'admin@waggy.local')->first();
        if ($admin) {
            $admin->update(['certificate_verified' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Unverify admin user certificate if rolling back
        $admin = User::where('email', 'admin@waggy.local')->first();
        if ($admin) {
            $admin->update(['certificate_verified' => false]);
        }
    }
};
