<?php

namespace App\Http\Controllers;

use App\Models\GroupCart;
use App\Services\GroupCartPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartContributionController extends Controller
{
    /**
     * Add or update the current neighbor's contribution to a group cart.
     */
    public function store(
        Request $request,
        GroupCart $groupCart,
        GroupCartPricingService $pricingService
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $groupCart->load('groceryItem');

        if ($groupCart->deadline_at->isPast()) {
            $groupCart->update([
                'status' => GroupCart::STATUS_EXPIRED,
            ]);

            return back()->withErrors([
                'quantity_kg' => 'This group cart deadline has already passed.',
            ]);
        }

        if ($groupCart->status === GroupCart::STATUS_ORDERED) {
            return back()->withErrors([
                'quantity_kg' => 'This group cart has already been ordered.',
            ]);
        }

        $neighborProfile = $user->neighborProfile;

        if (!$neighborProfile || $neighborProfile->apartment_building !== $groupCart->apartment_building) {
            return back()->withErrors([
                'quantity_kg' => 'You can only join group carts from your own apartment building.',
            ]);
        }

        $validated = $request->validate([
            'quantity_kg' => ['required', 'numeric', 'min:0.01'],
        ]);

        $quantityGrams = (int) round($validated['quantity_kg'] * 1000);

        if ($quantityGrams < $groupCart->groceryItem->minimum_contribution_grams) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity_kg' => 'Minimum contribution for this item is '
                        . number_format($groupCart->groceryItem->minimum_contribution_grams / 1000, 2)
                        . ' kg.',
                ]);
        }

        DB::transaction(function () use ($groupCart, $user, $quantityGrams, $pricingService) {
            $groupCart->contributions()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'quantity_grams' => $quantityGrams,
                    'estimated_amount_paisa' => 0,
                ]
            );

            $this->recalculateCartTotals($groupCart, $pricingService);
        });

        return redirect()
            ->route('group-carts.show', $groupCart)
            ->with('status', 'contribution-saved');
    }

    /**
     * Remove the current neighbor's contribution from a group cart.
     */
    public function destroy(
        GroupCart $groupCart,
        GroupCartPricingService $pricingService
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        if ($groupCart->deadline_at->isPast()) {
            return back()->withErrors([
                'quantity_kg' => 'You cannot remove a contribution after the deadline.',
            ]);
        }

        if ($groupCart->status === GroupCart::STATUS_ORDERED) {
            return back()->withErrors([
                'quantity_kg' => 'You cannot remove a contribution after the cart is ordered.',
            ]);
        }

        DB::transaction(function () use ($groupCart, $user, $pricingService) {
            $groupCart->contributions()
                ->where('user_id', $user->id)
                ->delete();

            $this->recalculateCartTotals($groupCart, $pricingService);
        });

        return redirect()
            ->route('group-carts.show', $groupCart)
            ->with('status', 'contribution-removed');
    }

    /**
     * Recalculate total cart weight, status, and every neighbor's bill.
     */
    private function recalculateCartTotals(
        GroupCart $groupCart,
        GroupCartPricingService $pricingService
    ): void {
        $totalWeightGrams = (int) $groupCart->contributions()->sum('quantity_grams');

        $newStatus = $totalWeightGrams >= $groupCart->target_weight_grams
            ? GroupCart::STATUS_THRESHOLD_MET
            : GroupCart::STATUS_ACTIVE;

        $groupCart->update([
            'current_weight_grams' => $totalWeightGrams,
            'status' => $newStatus,
        ]);

        $freshGroupCart = $groupCart->fresh(['groceryItem', 'contributions']);

        if ($freshGroupCart) {
            $pricingService->refreshContributionAmounts($freshGroupCart);
        }
    }
}