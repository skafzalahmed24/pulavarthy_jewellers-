<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvestmentPlan;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvestmentPlan::create([
            'name' => 'Gold Saver Plan',
            'description' => 'Secure your dream jewellery with small monthly steps. Best for weddings and long-term savings.',
            'term' => '11 Months',
            'base_deposit' => '₹5,000+',
            'bonus_benefit' => '1 Month Free',
            'features' => [
                'No making charges on redemption',
                'Locked gold rate guarantee'
            ],
            'is_popular' => true,
            'icon' => 'fas fa-gem',
            'button_text' => 'Get Started Now'
        ]);

        InvestmentPlan::create([
            'name' => 'Elite Diamond Plan',
            'description' => 'Exclusive benefit strategy for premium diamond collections. High flexibility and maximum returns.',
            'term' => '12 Months',
            'base_deposit' => '₹10,000+',
            'bonus_benefit' => '1.5 Months Free',
            'features' => [
                'Special diamond discounter coupons',
                'Personalised jewellery styling'
            ],
            'is_popular' => false,
            'icon' => 'fas fa-crown',
            'button_text' => 'Explore Elite'
        ]);
    }
}