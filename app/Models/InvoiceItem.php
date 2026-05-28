<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'product_id', 'description',
        'quantity', 'unit', 'unit_price',
        'is_taxable', 'tax_rate', 'discount_amount',
        'line_total', 'sort_order',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'quantity'   => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate'   => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function product() { return $this->belongsTo(Product::class); }
}