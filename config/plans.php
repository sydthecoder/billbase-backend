<?php

/*
|--------------------------------------------------------------------------
| Plan Features & Limits
|--------------------------------------------------------------------------
|
| This file is the single source of truth for what each plan includes.
|
| - "features"  : Marketing copy shown on pricing page / plan cards.
|                 Update these when your messaging changes.
|
| - "limits"    : Enforced by PlanGate service in code.
|                 null = unlimited.
|                 false = feature not available on this plan.
|
| Plans are identified by their slug (matches the `slug` column in DB).
| When adding a new plan: add it to DB via seeder AND add it here.
|
| Prices live in the DB (needed for billing integrations).
| Feature gates live here (code-level decisions, deployed not queried).
|
*/

return [

    'starter' => [

        'features' => [
            'Up to 200 invoices per month',
            '1 invoice template',
            '1 user',
            'Basic reporting',
            'Email support',
        ],

        'limits' => [
            'invoices_per_month' => 200,
            'customers'          => 50,
            'users'              => 1,
            'templates'          => 1,
            'custom_branding'    => false,
            'mail_settings'      => false,   // must use PayFlow default mailer
            'reporting'          => 'basic',
            'quotes'             => true,
            'bank_account'       => true,
        ],

    ],

    'professional' => [

        'features' => [
            'Up to 1000 invoices per month',
            '5 invoice templates',
            'Up to 5 users',
            'Advanced reporting',
            'Customer management',
            'Custom mail settings',
            'Priority email support',
        ],

        'limits' => [
            'invoices_per_month' => 1000,
            'customers'          => 500,
            'users'              => 5,
            'templates'          => 5,
            'custom_branding'    => true,
            'mail_settings'      => true,
            'reporting'          => 'advanced',
            'quotes'             => true,
            'bank_account'       => true,
        ],

    ],

    'enterprise' => [

        'features' => [
            'Unlimited invoices',
            'All invoice templates',
            'Unlimited users',
            'Full reporting & analytics',
            'Customer management',
            'Custom branding',
            'Custom mail settings',
            'PayFast recurring billing',
            'Dedicated support',
        ],

        'limits' => [
            'invoices_per_month' => null,    // unlimited
            'customers'          => null,
            'users'              => null,
            'templates'          => null,
            'custom_branding'    => true,
            'mail_settings'      => true,
            'reporting'          => 'full',
            'quotes'             => true,
            'bank_account'       => true,
        ],

    ],

];