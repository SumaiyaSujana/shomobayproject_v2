<?php

namespace App\Http\Controllers;

use App\Models\CartContribution;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeliveryCoordinatorController extends Controller
{
    /**
     * Select one neighbor as delivery coordinator and apply a 5% discount.
     */
    public function assign(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor() || $user->isAdmin(), 403);

        $order->load('groupCart.contributions.user');

        if (!$user->isAdmin() && $order->groupCart->created_by_user_id !== $user->id) {
            return back()->withErrors([
                'coordinator' => 'Only the group cart creator or admin can select a delivery coordinator.',
            ]);
        }

        if ($order->status !== Order::STATUS_ESCROW_HELD) {
            return back()->withErrors([
                'coordinator' => 'A coordinator can only be selected while the order is still in escrow.',
            ]);
        }

        if ($order->delivery_coordinator_user_id) {
            return back()->withErrors([
                'coordinator' => 'A delivery coordinator has already been selected for this order.',
            ]);
        }

        $validated = $request->validate([
            'delivery_coordinator_user_id' => ['required', 'exists:users,id'],
        ]);

        $coordinatorContribution = $order->groupCart->contributions
            ->firstWhere('user_id', (int) $validated['delivery_coordinator_user_id']);

        if (!$coordinatorContribution) {
            return back()->withErrors([
                'coordinator' => 'The selected coordinator must be one of the neighbors who contributed to this cart.',
            ]);
        }

        DB::transaction(function () use ($order, $coordinatorContribution) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->delivery_coordinator_user_id) {
                throw ValidationException::withMessages([
                    'coordinator' => 'A delivery coordinator has already been selected for this order.',
                ]);
            }

            $escrowTransaction = Transaction::where('order_id', $lockedOrder->id)
                ->where('type', 'escrow_hold')
                ->whereHas('wallet', function ($query) use ($coordinatorContribution) {
                    $query->where('user_id', $coordinatorContribution->user_id);
                })
                ->first();

            if (!$escrowTransaction) {
                throw ValidationException::withMessages([
                    'coordinator' => 'Escrow payment was not found for the selected coordinator.',
                ]);
            }

            $discountPaisa = (int) round($escrowTransaction->amount_paisa * 0.05);

            if ($discountPaisa <= 0) {
                throw ValidationException::withMessages([
                    'coordinator' => 'Coordinator discount could not be calculated.',
                ]);
            }

            $wallet = Wallet::where('user_id', $coordinatorContribution->user_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->escrow_paisa < $discountPaisa) {
                throw ValidationException::withMessages([
                    'coordinator' => 'The selected coordinator does not have enough escrow balance for the discount.',
                ]);
            }

            $wallet->update([
                'balance_paisa' => $wallet->balance_paisa + $discountPaisa,
                'escrow_paisa' => $wallet->escrow_paisa - $discountPaisa,
            ]);

            Transaction::create([
                'wallet_id' => $wallet->id,
                'group_cart_id' => $lockedOrder->group_cart_id,
                'order_id' => $lockedOrder->id,
                'amount_paisa' => $discountPaisa,
                'type' => 'coordinator_discount',
                'status' => 'completed',
                'description' => '5% delivery coordinator discount returned from escrow to wallet balance.',
            ]);

            $lockedOrder->update([
                'delivery_coordinator_user_id' => $coordinatorContribution->user_id,
                'coordinator_discount_paisa' => $discountPaisa,
                'coordinator_selected_at' => now(),
            ]);
        });

        return redirect()
            ->route('group-carts.show', $order->groupCart)
            ->with('status', 'coordinator-selected');
    }
}