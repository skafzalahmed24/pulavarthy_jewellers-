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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'plan_category')) {
                $table->dropColumn('plan_category');
            }
            if (Schema::hasColumn('users', 'scheme_number')) {
                $table->dropColumn('scheme_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan_category')->nullable();
            $table->string('scheme_number')->nullable()->unique();
        });
    }
};
