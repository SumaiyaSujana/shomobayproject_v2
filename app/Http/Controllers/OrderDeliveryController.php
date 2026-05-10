<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\EscrowReleaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrderDeliveryController extends Controller
{
    /**
     * Mark an order as delivered and release escrow money to vendor.
     */
    public function markDelivered(
        Order $order,
        EscrowReleaseService $escrowReleaseService
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless($user->isNeighbor() || $user->isAdmin(), 403);

        $order->load('groupCart');

        if (!$user->isAdmin() && $order->groupCart->created_by_user_id !== $user->id) {
            return back()->withErrors([
                'delivery' => 'Only the group cart creator or admin can mark this order as delivered.',
            ]);
        }

        $escrowReleaseService->releaseEscrowToVendor($order);

        return redirect()
    ->route('claim-tokens.index')
    ->with('status', 'order-delivered');
    }
}