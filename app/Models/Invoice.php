<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id', 'customer_id', 'quote_id',
        'invoice_number', 'status',
        'issue_date', 'due_date',
        'subtotal', 'discount_amount', 'discount_percent',
        'tax_total', 'total', 'amount_paid',
        'notes', 'footer', 'pdf_path',
        'sent_at', 'viewed_at', 'paid_at', 'is_locked',
        'billing_name', 'billing_company', 'billing_vat_number',
        'billing_street_address', 'billing_suburb', 'billing_city',
        'billing_province', 'billing_postal_code',
        'created_by',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'due_date'    => 'date',
        'sent_at'     => 'datetime',
        'viewed_at'   => 'datetime',
        'paid_at'     => 'datetime',
        'is_locked'   => 'boolean',
        'subtotal'    => 'decimal:2',
        'tax_total'   => 'decimal:2',
        'total'       => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    // Calculated — never stored
    public function getAmountDueAttribute(): float
    {
        return max(0, (float) $this->total - (float) $this->amount_paid);
    }

    public function getIsOverdueAttribute(): bool
    {
        return in_array($this->status, ['sent', 'partial', 'viewed'])
            && $this->due_date->isPast();
    }

    public function organization() { return $this->belongsTo(Organization::class); }
    public function customer()     { return $this->belongsTo(Customer::class); }
    public function quote()        { return $this->belongsTo(Quote::class); }
    public function items()        { return $this->hasMany(InvoiceItem::class)->orderBy('sort_order'); }
    public function payments()     { return $this->hasMany(Payment::class); }
    public function createdBy()    { return $this->belongsTo(User::class, 'created_by'); }
}