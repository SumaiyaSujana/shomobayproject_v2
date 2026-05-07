<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_NEIGHBOR = 'neighbor';
    public const ROLE_VENDOR = 'vendor';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function neighborProfile(): HasOne
    {
        return $this->hasOne(NeighborProfile::class);
    }

    public function vendorProfile(): HasOne
    {
        return $this->hasOne(VendorProfile::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function createdGroupCarts(): HasMany
    {
        return $this->hasMany(GroupCart::class, 'created_by_user_id');
    }

    public function cartContributions(): HasMany
    {
        return $this->hasMany(CartContribution::class);
    }

    public function vendorBids(): HasMany
    {
        return $this->hasMany(Bid::class, 'vendor_user_id');
    }

    public function ratingsGiven(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function ratingsReceivedAsVendor(): HasMany
    {
        return $this->hasMany(Rating::class, 'vendor_user_id');
    }

    public function isNeighbor(): bool
    {
        return $this->role === self::ROLE_NEIGHBOR;
    }

    public function isVendor(): bool
    {
        return $this->role === self::ROLE_VENDOR;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}