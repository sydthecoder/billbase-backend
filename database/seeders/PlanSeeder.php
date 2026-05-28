<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'       => 'Starter',
                'slug'       => 'starter',
                'price'      => 0.00,
                'features'   => json_encode([
                    'Up to 100 invoices per month',
                    '1 invoice template',
                    '1 user',
                    'Basic reporting',
                    'Email support',
                ]),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Professional',
                'slug'       => 'professional',
                'price'      => 299.00,
                'features'   => json_encode([
                    'Up to 100 invoices per month',
                    '5 invoice templates',
                    'Up to 5 users',
                    'Advanced reporting',
                    'Customer management',
                    'Priority email support',
                ]),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Enterprise',
                'slug'       => 'enterprise',
                'price'      => 799.00,
                'features'   => json_encode([
                    'Unlimited invoices',
                    'All invoice templates',
                    'Unlimited users',
                    'Full reporting & analytics',
                    'Customer management',
                    'PayFast recurring billing',
                    'Dedicated support',
                    'Custom branding',
                ]),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('plans')->insert($plans);
    }
}