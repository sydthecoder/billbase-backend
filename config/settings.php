<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tax Defaults
    |--------------------------------------------------------------------------
    |
    | These are the system-wide defaults for tax settings.
    | They are used on products if they are table, then applied automatically on quotes/invoices line items.
    |
    |
    */

    'tax' => [
        'rate'  => 15.00,   // South African VAT — fixed by SARS, tenant can't change it or decide rate
        'label' => 'VAT',
        'number_label' => 'VAT No.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization Preference Defaults
    |--------------------------------------------------------------------------
    |
    | These are the system-wide defaults for all organizations.
    | They are used when a tenant has not yet saved their own preferences.
    | When a tenant updates a preference, their value is saved to the
    | organization_preferences table and takes priority over these defaults.
    |
    | To change a default for ALL new and unconfigured tenants, change it here.
    | Never hardcode these values anywhere else in the application.
    |
    */

    'organization_preferences' => [

        /*
        |----------------------------------------------------------------------
        | Invoice Settings
        |----------------------------------------------------------------------
        */

        'invoice_prefix' => 'INV',

        // The number the invoice sequence starts at for new organizations
        'invoice_starting_number' => 1,

        // Default number of days before an invoice is due
        // e.g. 30 = Net 30 (due 30 days after invoice date)
        'default_payment_terms' => 30,

        'invoice_footer' => 'Thank you for your business.',
        'invoice_notes' => null,
        'invoice_template' => 'default',

        /*
        |----------------------------------------------------------------------
        | Quote Settings
        |----------------------------------------------------------------------
        */

        // Prefix used when generating quote numbers e.g. QUO-0001
        'quote_prefix' => 'QUO',

        // The number the quote sequence starts at for new organizations
        'quote_starting_number' => 1,
        'quote_template'   => 'default',

        /*
        |----------------------------------------------------------------------
        | Customer Settings
        |----------------------------------------------------------------------
        */

        // Prefix used when generating customer codes e.g. CUST-4821
        // Tenants can change this to their own brand e.g. CLI, ACC, PAX
        'customer_code_prefix' => 'BB',

        /*
        |----------------------------------------------------------------------
        | Branding
        |----------------------------------------------------------------------
        */

        // Primary brand color shown on invoice PDFs and customer portal
        // Must be a valid hex color code
        'brand_color' => '#0F766E',

    ],

];