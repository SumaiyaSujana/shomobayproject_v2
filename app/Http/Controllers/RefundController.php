<?php

namespace App\Http\Controllers;

use App\Models\GroupCart;
use App\Models\Order;
use App\Services\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    /**
     * Mark a failed group cart as expired.
     */
    public function expireGroupCart(
        GroupCart $groupCart,
        RefundService $refundService
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless($user->isNeighbor() || $user->isAdmin(), 403);

        if (!$user->isAdmin() && $groupCart->created_by_user_id !== $user->id) {
            return back()->withErrors([
                'refund' => 'Only the group cart creator or admin can trigger this refund action.',
            ]);
        }

        $refundService->expireFailedGroupCart($groupCart);

        return redirect()
            ->route('group-carts.show', $groupCart)
            ->with('status', 'group-cart-expired');
    }

    /**
     * Refund escrow money for an accepted order.
     */
    public function refundOrder(
        Order $order,
        RefundService $refundService
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless($user->isNeighbor() || $user->isAdmin(), 403);

        $order->load('groupCart');

        if (!$user->isAdmin() && $order->groupCart->created_by_user_id !== $user->id) {
            return back()->withErrors([
                'refund' => 'Only the group cart creator or admin can refund this order.',
            ]);
        }

        $refundService->refundEscrowForOrder($order);

        return redirect()
            ->route('group-carts.show', $order->groupCart)
            ->with('status', 'order-refunded');
    }
}