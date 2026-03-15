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
        Schema::create('user_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('scheme_id'); // foreign id to investment_plans, but avoiding naming conflicts if investment_plans doesn't exist yet, we can constrain it later or directly
            $table->foreign('scheme_id')->references('id')->on('investment_plans')->onDelete('cascade');
            $table->string('scheme_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_schemes');
    }
};
