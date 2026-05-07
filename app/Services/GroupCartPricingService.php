<?php

namespace App\Services;

use App\Models\GroceryItem;
use App\Models\GroupCart;

class GroupCartPricingService
{
    /**
     * Calculate the current per kg price.
     *
     * The price gradually drops from market price to wholesale price
     * as the cart approaches the target weight.
     */
    public function currentPricePerKgPaisa(GroupCart $groupCart): int
    {
        $item = $groupCart->groceryItem;

        if ($groupCart->target_weight_grams <= 0) {
            return $item->market_price_per_kg_paisa;
        }

        $progress = min(
            $groupCart->current_weight_grams / $groupCart->target_weight_grams,
            1
        );

        $discountRange = $item->market_price_per_kg_paisa - $item->wholesale_price_per_kg_paisa;

        return (int) round(
            $item->market_price_per_kg_paisa - ($discountRange * $progress)
        );
    }

    /**
     * Calculate estimated amount for a given quantity.
     */
    public function estimatedAmountPaisa(GroupCart $groupCart, int $quantityGrams): int
    {
        $pricePerKgPaisa = $this->currentPricePerKgPaisa($groupCart);

        return (int) round(($quantityGrams / 1000) * $pricePerKgPaisa);
    }

    /**
     * Calculate progress percentage toward target.
     */
    public function progressPercentage(GroupCart $groupCart): int
    {
        if ($groupCart->target_weight_grams <= 0) {
            return 0;
        }

        return (int) min(
            round(($groupCart->current_weight_grams / $groupCart->target_weight_grams) * 100),
            100
        );
    }

    /**
     * Check if checkout should be allowed.
     */
    public function canCheckout(GroupCart $groupCart): bool
    {
        return $groupCart->current_weight_grams >= $groupCart->target_weight_grams;
    }

    /**
     * Convert paisa to taka.
     */
    public function paisaToTaka(int $paisa): string
    {
        return number_format($paisa / 100, 2);
    }
}