<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
        ['email' => 'customer@gmail.com'],
        [
            'name' => 'Demo Customer',
            'password' => Hash::make('Customer@123'),
            'mobile' => '9876543210',
            'address' => '123 Luxury Lane',
            'city' => 'Vijayawada',
            'pincode' => '520001',
            'state' => 'Andhra Pradesh',
            'identity_proof' => 'ABCDE1234F',
            'plan_category' => 'Gold Saver (11 Months)',
            'monthly_contribution' => '5000',
            'nominee_name' => 'Demo Nominee',
            'nominee_relationship' => 'Spouse',
            'nominee_contact' => '9876543211',
            'is_admin' => false,
        ]
        );
    }
}