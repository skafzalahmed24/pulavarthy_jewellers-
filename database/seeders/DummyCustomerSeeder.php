<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyCustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            User::create([
                'name' => "Dummy Customer $i",
                'email' => "dummy_user_" . uniqid() . "@example.com",
                'password' => Hash::make('password'),
                'is_admin' => false,
                'mobile' => '90000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'address' => 'Dummy Road ' . $i,
                'city' => 'Nellore',
                'pincode' => '524001',
                'state' => 'Andhra Pradesh',
                'plan_category' => $i % 2 == 0 ? 'Gold' : 'Silver',
                'monthly_contribution' => 1000 * ($i % 5 + 1),
                'status' => $i % 3 == 0 ? 'pending' : 'approved',
            ]);
        }
    }
}