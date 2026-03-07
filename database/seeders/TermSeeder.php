<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Term::create([
            'icon' => 'fas fa-info-circle',
            'content' => 'All jewellery purchase plans are subject to company policies and prevailing market rates at the time of maturity or redemption.'
        ]);

        \App\Models\Term::create([
            'icon' => 'fas fa-exclamation-triangle',
            'content' => 'Monthly installment payments must be completed before the due date to ensure continuous eligibility for bonus benefits and to avoid penalties.'
        ]);

        \App\Models\Term::create([
            'icon' => 'fas fa-store',
            'content' => 'Jewellery redemption is valid only at our registered store: XYZ Jewellers, MG Road, Vijayawada, Andhra Pradesh, with valid identification.'
        ]);
    }
}