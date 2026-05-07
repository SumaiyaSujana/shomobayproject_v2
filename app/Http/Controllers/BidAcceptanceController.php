<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\GroupCart;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BidAcceptanceController extends Controller
{
    /**
     * Accept a vendor bid, create an order, and move neighbor payments into escrow.
     */
    public function accept(GroupCart $groupCart, Bid $bid): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        if ($groupCart->created_by_user_id !== $user->id) {
            return back()->withErrors([
                'bid' => 'Only the group cart creator can accept a vendor bid.',
            ]);
        }

        if ($bid->group_cart_id !== $groupCart->id) {
            return back()->withErrors([
                'bid' => 'This bid does not belong to this group cart.',
            ]);
        }

        DB::transaction(function () use ($groupCart, $bid, $user) {
            $lockedGroupCart = GroupCart::whereKey($groupCart->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedBid = Bid::whereKey($bid->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedGroupCart->status !== GroupCart::STATUS_THRESHOLD_MET) {
                throw ValidationException::withMessages([
                    'bid' => 'This group cart is not ready for bid acceptance.',
                ]);
            }

            if ($lockedGroupCart->order()->exists()) {
                throw ValidationException::withMessages([
                    'bid' => 'A bid has already been accepted for this cart.',
                ]);
            }

            $lockedGroupCart->load(['contributions.user']);

            if ($lockedGroupCart->contributions->isEmpty()) {
                throw ValidationException::withMessages([
                    'bid' => 'This cart has no neighbor contributions.',
                ]);
            }

            $itemAmountPaisa = (int) round(
                ($lockedGroupCart->current_weight_grams / 1000) * $lockedBid->price_per_kg_paisa
            );

            $deliveryFeePaisa = $lockedBid->delivery_fee_paisa;
            $totalAmountPaisa = $itemAmountPaisa + $deliveryFeePaisa;

            $payables = $this->calculateContributorPayables(
                $lockedGroupCart,
                $lockedBid,
                $totalAmountPaisa
            );

            foreach ($lockedGroupCart->contributions as $contribution) {
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $contribution->user_id],
                    [
                        'balance_paisa' => 0,
                        'escrow_paisa' => 0,
                    ]
                );

                $payablePaisa = $payables[$contribution->id];

                if ($wallet->balance_paisa < $payablePaisa) {
                    throw ValidationException::withMessages([
                        'bid' => $contribution->user->name . ' does not have enough wallet balance for escrow.',
                    ]);
                }
            }

            $order = Order::create([
                'group_cart_id' => $lockedGroupCart->id,
                'bid_id' => $lockedBid->id,
                'accepted_by_user_id' => $user->id,
                'vendor_user_id' => $lockedBid->vendor_user_id,
                'item_amount_paisa' => $itemAmountPaisa,
                'delivery_fee_paisa' => $deliveryFeePaisa,
                'total_amount_paisa' => $totalAmountPaisa,
                'status' => Order::STATUS_ESCROW_HELD,
            ]);

            foreach ($lockedGroupCart->contributions as $contribution) {
                $wallet = Wallet::where('user_id', $contribution->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $payablePaisa = $payables[$contribution->id];

                $wallet->update([
                    'balance_paisa' => $wallet->balance_paisa - $payablePaisa,
                    'escrow_paisa' => $wallet->escrow_paisa + $payablePaisa,
                ]);

                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'group_cart_id' => $lockedGroupCart->id,
                    'order_id' => $order->id,
                    'amount_paisa' => $payablePaisa,
                    'type' => 'escrow_hold',
                    'status' => 'completed',
                    'description' => 'Funds moved from wallet balance to escrow after vendor bid acceptance.',
                ]);
            }

            $lockedBid->update([
                'status' => Bid::STATUS_ACCEPTED,
            ]);

            Bid::where('group_cart_id', $lockedGroupCart->id)
                ->where('id', '!=', $lockedBid->id)
                ->update([
                    'status' => Bid::STATUS_REJECTED,
                ]);

            $lockedGroupCart->update([
                'status' => GroupCart::STATUS_ORDERED,
            ]);
        });

        return redirect()
            ->route('group-carts.show', $groupCart)
            ->with('status', 'bid-accepted');
    }

    /**
     * Split accepted bid amount among neighbors based on contributed quantity.
     */
    private function calculateContributorPayables(
        GroupCart $groupCart,
        Bid $bid,
        int $totalAmountPaisa
    ): array {
        $payables = [];
        $allocatedPaisa = 0;

        $contributions = $groupCart->contributions->values();
        $lastIndex = $contributions->count() - 1;

        foreach ($contributions as $index => $contribution) {
            if ($index === $lastIndex) {
                $payables[$contribution->id] = $totalAmountPaisa - $allocatedPaisa;
                break;
            }

            $itemCostPaisa = (int) round(
                ($contribution->quantity_grams / 1000) * $bid->price_per_kg_paisa
            );

            $deliverySharePaisa = (int) round(
                ($contribution->quantity_grams / $groupCart->current_weight_grams) * $bid->delivery_fee_paisa
            );

            $payablePaisa = $itemCostPaisa + $deliverySharePaisa;

            $payables[$contribution->id] = $payablePaisa;
            $allocatedPaisa += $payablePaisa;
        }

        return $payables;
    }
}