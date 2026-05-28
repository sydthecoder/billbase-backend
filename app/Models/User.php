<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'role',
        'avatar_path',
        'last_login_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password'      => 'hashed',
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Accessors
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path
            ? asset('storage/avatars/' . $this->avatar_path)
            : null;
    }
}