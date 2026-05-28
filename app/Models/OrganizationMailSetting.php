<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMailSetting extends Model
{
    protected $fillable = [
        'organization_id',
        'driver',
        'from_name',
        'from_email',
        'config',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Encrypt on save, decrypt on get
    public function setConfigAttribute(array $value): void
    {
        $this->attributes['config'] = encrypt(json_encode($value));
    }

    public function getConfigAttribute(string $value): array
    {
        return json_decode(decrypt($value), true);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}