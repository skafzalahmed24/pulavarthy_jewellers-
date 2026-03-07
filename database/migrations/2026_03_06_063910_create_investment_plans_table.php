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
        Schema::create('investment_plans', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->text('description');
            $blueprint->string('term');
            $blueprint->string('base_deposit');
            $blueprint->string('bonus_benefit');
            $blueprint->json('features')->nullable();
            $blueprint->boolean('is_popular')->default(false);
            $blueprint->string('icon')->default('fas fa-gem');
            $blueprint->string('button_text')->default('Get Started Now');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};