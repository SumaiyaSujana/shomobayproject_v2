<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EscrowReleaseService
{
    /**
     * Release escrow-held money to the accepted vendor after successful delivery.
     */
    public function releaseEscrowToVendor(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status !== Order::STATUS_ESCROW_HELD) {
                throw ValidationException::withMessages([
                    'delivery' => 'Only escrow-held orders can be marked as delivered.',
                ]);
            }

            $escrowTransactions = Transaction::where('order_id', $lockedOrder->id)
                ->where('type', 'escrow_hold')
                ->get();

            if ($escrowTransactions->isEmpty()) {
                throw ValidationException::withMessages([
                    'delivery' => 'No escrow transactions were found for this order.',
                ]);
            }

            $totalReleasedPaisa = 0;

            foreach ($escrowTransactions as $escrowTransaction) {
                $wallet = Wallet::whereKey($escrowTransaction->wallet_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $releaseAmountPaisa = $escrowTransaction->amount_paisa;

                if ($wallet->escrow_paisa < $releaseAmountPaisa) {
                    throw ValidationException::withMessages([
                        'delivery' => 'One participant wallet does not have enough escrow balance to release.',
                    ]);
                }

                $wallet->update([
                    'escrow_paisa' => $wallet->escrow_paisa - $releaseAmountPaisa,
                ]);

                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'group_cart_id' => $escrowTransaction->group_cart_id,
                    'order_id' => $lockedOrder->id,
                    'amount_paisa' => $releaseAmountPaisa,
                    'type' => 'escrow_release',
                    'status' => 'completed',
                    'description' => 'Escrow money released after successful delivery.',
                ]);

                $totalReleasedPaisa += $releaseAmountPaisa;
            }

            $vendorWallet = Wallet::firstOrCreate(
                ['user_id' => $lockedOrder->vendor_user_id],
                [
                    'balance_paisa' => 0,
                    'escrow_paisa' => 0,
                ]
            );

            $vendorWallet->increment('balance_paisa', $totalReleasedPaisa);

            Transaction::create([
                'wallet_id' => $vendorWallet->id,
                'group_cart_id' => $lockedOrder->group_cart_id,
                'order_id' => $lockedOrder->id,
                'amount_paisa' => $totalReleasedPaisa,
                'type' => 'vendor_payment',
                'status' => 'completed',
                'description' => 'Vendor received payment after successful delivery.',
            ]);

            $lockedOrder->update([
                'status' => Order::STATUS_DELIVERED,
            ]);
        });
    }
}