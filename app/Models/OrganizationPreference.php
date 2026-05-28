<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationPreference extends Model
{
    protected $fillable = [
        'organization_id',
        'invoice_prefix',
        'invoice_starting_number',
        'default_payment_terms',
        'invoice_footer',
        'invoice_notes',
        'quote_prefix',
        'quote_starting_number',
        'customer_code_prefix',
        'brand_color',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}