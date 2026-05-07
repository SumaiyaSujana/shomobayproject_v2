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

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Fields hidden from array/json output.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * A user may have one neighbor profile.
     */
    public function neighborProfile(): HasOne
    {
        return $this->hasOne(NeighborProfile::class);
    }

    /**
     * A user may have one vendor profile.
     */
    public function vendorProfile(): HasOne
    {
        return $this->hasOne(VendorProfile::class);
    }

    /**
     * A user has one wallet.
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Group carts created by this user.
     */
    public function createdGroupCarts(): HasMany
    {
        return $this->hasMany(GroupCart::class, 'created_by_user_id');
    }

    /**
     * Cart contributions made by this user.
     */
    public function cartContributions(): HasMany
    {
        return $this->hasMany(CartContribution::class);
    }

    /**
     * Check if the user is a neighbor.
     */
    public function isNeighbor(): bool
    {
        return $this->role === self::ROLE_NEIGHBOR;
    }

    /**
     * Check if the user is a vendor.
     */
    public function isVendor(): bool
    {
        return $this->role === self::ROLE_VENDOR;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}