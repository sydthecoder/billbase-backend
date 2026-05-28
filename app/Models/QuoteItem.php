<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'product_id',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'is_taxable',
        'tax_rate',
        'discount_amount',
        'line_total',
        'sort_order',
    ];

    protected $casts = [
        'quantity'        => 'decimal:2',
        'unit_price'      => 'decimal:2',
        'tax_rate'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total'      => 'decimal:2',
        'is_taxable'      => 'boolean',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}