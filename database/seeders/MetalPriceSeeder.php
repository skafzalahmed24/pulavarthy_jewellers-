<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetalPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MetalPrice::updateOrCreate(
        ['metal_name' => 'Gold'],
        ['today_price' => 6450.00, 'yesterday_price' => 6400.00]
        );

        \App\Models\MetalPrice::updateOrCreate(
        ['metal_name' => 'Silver'],
        ['today_price' => 78.50, 'yesterday_price' => 80.00]
        );
    }
}