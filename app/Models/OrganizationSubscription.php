<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationSubscription extends Model
{
    const STATUS_TRIALING   = 'trialing';
    const STATUS_ACTIVE     = 'active';
    const STATUS_PAST_DUE   = 'past_due';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_EXPIRED    = 'expired';

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

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isTrialing(): bool
    {
        return $this->status === self::STATUS_TRIALING;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPastDue(): bool
    {
        return $this->status === self::STATUS_PAST_DUE;
    }

    /**
     * Is the org on a usable plan right now?
     * Active or trialing = yes. Everything else = no.
     */
    public function isUsable(): bool
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_TRIALING]);
    }
}