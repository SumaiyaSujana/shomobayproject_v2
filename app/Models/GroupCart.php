<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupCart extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_THRESHOLD_MET = 'threshold_met';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_ORDERED = 'ordered';

    protected $fillable = [
        'created_by_user_id',
        'grocery_item_id',
        'title',
        'apartment_building',
        'location_coordinates',
        'target_weight_grams',
        'current_weight_grams',
        'deadline_at',
        'status',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
    ];

    /**
     * The neighbor who created the group cart.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * The grocery item being purchased.
     */
    public function groceryItem(): BelongsTo
    {
        return $this->belongsTo(GroceryItem::class);
    }

    /**
     * Neighbor contributions for this cart.
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(CartContribution::class);
    }

    /**
     * Vendor bids for this cart.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Check whether the cart reached wholesale threshold.
     */
    public function hasReachedThreshold(): bool
    {
        return $this->current_weight_grams >= $this->target_weight_grams;
    }

    /**
     * Show current weight in kg.
     */
    public function currentWeightInKg(): float
    {
        return $this->current_weight_grams / 1000;
    }

    /**
     * Show target weight in kg.
     */
    public function targetWeightInKg(): float
    {
        return $this->target_weight_grams / 1000;
    }
}