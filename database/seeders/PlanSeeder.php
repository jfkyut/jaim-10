<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'price' => '0',
                'features' => json_encode([
                    'Listening only - no downloads allowed',
                    'Ads supported',
                    'Limited access to features',
                    'For Listening Purposes Only',
                ]),
                'recommended' => false,
                'is_free' => true,
            ],
            [
                'name' => 'Premium',
                'price' => '99',
                'features' => json_encode([
                    'Unlimited downloads',
                    'Ad-free experience',
                    'Access to exclusive content',
                    'High-quality audio streaming',
                    'Able to create albums',
                    'Able to upload songs',
                ]),
                'recommended' => true,
                'is_free' => false,
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\Plan::create($plan);
        }
    }
}
