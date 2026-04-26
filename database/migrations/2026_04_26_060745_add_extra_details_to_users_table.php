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
            $table->string('pan_number')->nullable()->after('identity_proof');
            $table->string('identity_proof_type')->nullable()->after('identity_proof');
            $table->date('dob')->nullable()->after('nominee_contact');
            $table->date('wedding_anniversary')->nullable()->after('dob');
            $table->string('bank_acc_no')->nullable()->after('wedding_anniversary');
            $table->string('bank_branch')->nullable()->after('bank_acc_no');
            $table->string('ifsc_code')->nullable()->after('bank_branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pan_number', 'identity_proof_type', 'dob', 
                'wedding_anniversary', 'bank_acc_no', 
                'bank_branch', 'ifsc_code'
            ]);
        });
    }
};
