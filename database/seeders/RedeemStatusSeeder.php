<?php

namespace Database\Seeders;

use App\Models\RedeemStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedeemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'pending',
            'approved',
            'rejected'
        ];

        foreach ($statuses as $status) {
            RedeemStatus::create([
                'name' => $status
            ]);
        }
    }
}
