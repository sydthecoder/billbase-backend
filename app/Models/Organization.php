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

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Full subscription history — every plan the org has ever been on.
     * Use for billing history, audit logs, upgrade/downgrade records.
     */
    public function subscriptions()
    {
        return $this->hasMany(OrganizationSubscription::class)->orderByDesc('created_at');
    }

    /**
     * The current active subscription — what the org is on RIGHT NOW.
     * Use this everywhere you need to check plan, limits, trial status.
     *
     * latestOfMany() ensures if two rows are active during a transition,
     * we always get the newest — safe for upgrades/downgrades.
     */
    public function activeSubscription()
    {
        return $this->hasOne(OrganizationSubscription::class)
                    ->whereIn('status', ['active', 'trialing'])
                    ->latestOfMany();
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_filename
            ? asset('storage/logos/' . $this->logo_filename)
            : null;
    }
}