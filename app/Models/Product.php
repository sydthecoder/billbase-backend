<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'product_category_id',
        'name',
        'description',
        'price',
        'unit',
        'is_taxable',
        'sku',
        'status',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'is_taxable' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}