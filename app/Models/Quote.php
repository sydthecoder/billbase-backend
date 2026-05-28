<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'customer_id',
        'created_by',
        'quote_number',
        'title',
        'status',
        'issue_date',
        'expires_at',
        'subtotal',
        'discount_amount',
        'discount_percent',
        'tax_total',
        'total',
        'notes',
        'footer',
        'sent_at',
        'viewed_at',
        'converted_at',
        'converted_to_invoice_id',
    ];

    protected $casts = [
        'issue_date'   => 'date',
        'expires_at'   => 'date',
        'sent_at'      => 'datetime',
        'viewed_at'    => 'datetime',
        'converted_at' => 'datetime',
        'subtotal'     => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_total'    => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    // Statuses that are locked from editing
    public const LOCKED_STATUSES = ['accepted', 'declined', 'expired', 'converted'];

    public function isLocked(): bool
    {
        return in_array($this->status, self::LOCKED_STATUSES);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class)->orderBy('sort_order');
    }
}