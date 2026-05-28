<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationSubscription extends Model
{
    protected $fillable = [
        'organization_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'cancelled_at',
        'payfast_token',
        'payfast_subscription_token',
        'payfast_payment_id',
    ];

    protected $casts = [
        'trial_ends_at'        => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end'   => 'datetime',
        'cancelled_at'         => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}