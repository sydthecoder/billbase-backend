<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'description',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}