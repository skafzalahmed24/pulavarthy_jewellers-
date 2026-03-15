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
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('grace_extension_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->after('payment_status');
            $table->text('grace_extension_reason')->nullable()->after('grace_extension_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['grace_extension_status', 'grace_extension_reason']);
        });
    }
};
