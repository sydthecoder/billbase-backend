<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'customer_code',
        'customer_type',
        'company_name',
        'company_reg_number',
        'vat_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'street_address',
        'suburb',
        'city',
        'province',
        'postal_code',
        'notes',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices() { 
        return $this->hasMany(Invoice::class); 
    }

}