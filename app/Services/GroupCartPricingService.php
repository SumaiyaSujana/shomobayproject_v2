<?php

namespace App\Services;

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
        $groupCart->loadMissing('groceryItem');

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
     * Calculate estimated amount for a given quantity using the current cart price.
     */
    public function estimatedAmountPaisa(GroupCart $groupCart, int $quantityGrams): int
    {
        $pricePerKgPaisa = $this->currentPricePerKgPaisa($groupCart);

        return $this->estimatedAmountPaisaAtPrice($pricePerKgPaisa, $quantityGrams);
    }

    /**
     * Calculate estimated amount for a given quantity using a fixed per kg price.
     */
    public function estimatedAmountPaisaAtPrice(int $pricePerKgPaisa, int $quantityGrams): int
    {
        return (int) round(($quantityGrams / 1000) * $pricePerKgPaisa);
    }

    /**
     * Recalculate all neighbor bills after the cart weight changes.
     */
    public function refreshContributionAmounts(GroupCart $groupCart): void
    {
        $groupCart->loadMissing(['groceryItem', 'contributions']);

        $pricePerKgPaisa = $this->currentPricePerKgPaisa($groupCart);

        foreach ($groupCart->contributions as $contribution) {
            $contribution->update([
                'estimated_amount_paisa' => $this->estimatedAmountPaisaAtPrice(
                    $pricePerKgPaisa,
                    $contribution->quantity_grams
                ),
            ]);
        }
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
     * Calculate remaining weight before threshold is reached.
     */
    public function remainingWeightGrams(GroupCart $groupCart): int
    {
        return max(
            $groupCart->target_weight_grams - $groupCart->current_weight_grams,
            0
        );
    }

    /**
     * Convert paisa to taka.
     */
    public function paisaToTaka(int $paisa): string
    {
        return number_format($paisa / 100, 2);
    }
}