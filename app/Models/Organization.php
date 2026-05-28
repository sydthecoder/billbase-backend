<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'org_code',
        'name',
        'email',
        'phone',
        'reg_number',
        'tax_number',
        'street_address',
        'suburb',
        'city',
        'province',
        'postal_code',
        'country',
        'currency',
        'logo_filename',
        'status',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(OrganizationSubscription::class);
    }

    // Accessors
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_filename
            ? asset('storage/logos/' . $this->logo_filename)
            : null;
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function preferences()
    {
        return $this->hasOne(OrganizationPreference::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(OrganizationBankAccount::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices() { 
        return $this->hasMany(Invoice::class); 
    }

    public function payments() { 
        return $this->hasMany(Payment::class); 
    }
}