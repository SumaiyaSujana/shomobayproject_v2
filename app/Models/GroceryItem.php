<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroceryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'market_price_per_kg_paisa',
        'wholesale_price_per_kg_paisa',
        'minimum_bulk_weight_grams',
        'minimum_contribution_grams',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * A grocery item can be used in many group carts.
     */
    public function groupCarts(): HasMany
    {
        return $this->hasMany(GroupCart::class);
    }

    /**
     * Show market price in taka.
     */
    public function marketPriceInTaka(): float
    {
        return $this->market_price_per_kg_paisa / 100;
    }

    /**
     * Show wholesale price in taka.
     */
    public function wholesalePriceInTaka(): float
    {
        return $this->wholesale_price_per_kg_paisa / 100;
    }

    /**
     * Show minimum bulk weight in kg.
     */
    public function minimumBulkWeightInKg(): float
    {
        return $this->minimum_bulk_weight_grams / 1000;
    }

    /**
     * Show minimum contribution in kg.
     */
    public function minimumContributionInKg(): float
    {
        return $this->minimum_contribution_grams / 1000;
    }
}