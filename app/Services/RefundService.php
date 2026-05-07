<?php

namespace App\Services;

use App\Models\GroupCart;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService
{
    /**
     * Refund all escrow-held money for an order.
     */
    public function refundEscrowForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status !== Order::STATUS_ESCROW_HELD) {
                throw ValidationException::withMessages([
                    'refund' => 'Only escrow-held orders can be refunded.',
                ]);
            }

            $escrowTransactions = Transaction::where('order_id', $lockedOrder->id)
                ->where('type', 'escrow_hold')
                ->get();

            if ($escrowTransactions->isEmpty()) {
                throw ValidationException::withMessages([
                    'refund' => 'No escrow transactions were found for this order.',
                ]);
            }

            foreach ($escrowTransactions as $escrowTransaction) {
                $wallet = Wallet::whereKey($escrowTransaction->wallet_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $refundAmountPaisa = $escrowTransaction->amount_paisa;

                if ($wallet->escrow_paisa < $refundAmountPaisa) {
                    throw ValidationException::withMessages([
                        'refund' => 'One wallet does not have enough escrow balance to refund.',
                    ]);
                }

                $wallet->update([
                    'balance_paisa' => $wallet->balance_paisa + $refundAmountPaisa,
                    'escrow_paisa' => $wallet->escrow_paisa - $refundAmountPaisa,
                ]);

                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'group_cart_id' => $escrowTransaction->group_cart_id,
                    'order_id' => $lockedOrder->id,
                    'amount_paisa' => $refundAmountPaisa,
                    'type' => 'escrow_refund',
                    'status' => 'completed',
                    'description' => 'Escrow amount refunded back to wallet balance.',
                ]);
            }

            $lockedOrder->update([
                'status' => Order::STATUS_REFUNDED,
            ]);

            $lockedOrder->groupCart()->update([
                'status' => GroupCart::STATUS_EXPIRED,
            ]);
        });
    }

    /**
     * Mark a failed group cart as expired.
     *
     * In the current demo flow, money is only held in escrow after bid acceptance.
     * So failed pre-checkout carts do not need money movement.
     */
    public function expireFailedGroupCart(GroupCart $groupCart): void
    {
        DB::transaction(function () use ($groupCart) {
            $lockedGroupCart = GroupCart::whereKey($groupCart->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedGroupCart->order()->exists()) {
                throw ValidationException::withMessages([
                    'refund' => 'This cart already has an order. Use order refund instead.',
                ]);
            }

            if ($lockedGroupCart->status !== GroupCart::STATUS_ACTIVE) {
                throw ValidationException::withMessages([
                    'refund' => 'Only active carts can be marked as failed before checkout.',
                ]);
            }

            if ($lockedGroupCart->hasReachedThreshold()) {
                throw ValidationException::withMessages([
                    'refund' => 'This cart already reached the wholesale threshold and should not be marked as failed.',
                ]);
            }

            $lockedGroupCart->update([
                'status' => GroupCart::STATUS_EXPIRED,
            ]);
        });
    }
}