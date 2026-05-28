<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationBankAccount extends Model
{
    protected $fillable = [
        'organization_id',
        'bank_name',
        'account_holder',
        'account_number',
        'branch_code',
        'account_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}