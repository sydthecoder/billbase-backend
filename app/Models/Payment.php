<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'organization_id', 'invoice_id',
        'payment_method', 'gateway', 'gateway_reference', 'gateway_status',
        'amount', 'currency', 'status',
        'notes', 'paid_at', 'created_by',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function invoice()      { return $this->belongsTo(Invoice::class); }
    public function organization() { return $this->belongsTo(Organization::class); }
    public function createdBy()    { return $this->belongsTo(User::class, 'created_by'); }
}