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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_scheme_id')->constrained('user_schemes')->onDelete('cascade');
            $table->string('payment_id')->nullable(); // Razorpay payout or transaction ID
            $table->decimal('current_gold_rate', 10, 2)->nullable();
            $table->decimal('payable_amount', 10, 2);
            $table->date('due_date');
            $table->date('next_due_date')->nullable();
            $table->date('grace_start_date')->nullable();
            $table->date('grace_end_date')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
