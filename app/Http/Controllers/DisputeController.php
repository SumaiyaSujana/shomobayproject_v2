<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DisputeController extends Controller
{
    /**
     * Show neighbor's delivered orders and submitted disputes.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $orders = Order::with([
                'groupCart.groceryItem',
                'vendor.vendorProfile',
                'disputes' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
            ])
            ->where('status', Order::STATUS_DELIVERED)
            ->whereHas('groupCart.contributions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        $disputes = Dispute::with([
                'order.groupCart.groceryItem',
                'vendor.vendorProfile',
                'resolvedBy',
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('disputes.index', [
            'orders' => $orders,
            'disputes' => $disputes,
        ]);
    }

    /**
     * Store a dispute request for a delivered order.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $order->load('groupCart.contributions');

        if ($order->status !== Order::STATUS_DELIVERED) {
            return back()->withErrors([
                'dispute' => 'You can only dispute an order after it has been delivered.',
            ]);
        }

        $hasContribution = $order->groupCart->contributions
            ->contains('user_id', $user->id);

        if (!$hasContribution) {
            return back()->withErrors([
                'dispute' => 'Only contributors of this order can submit a dispute.',
            ]);
        }

        $alreadyDisputed = Dispute::where('order_id', $order->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyDisputed) {
            return back()->withErrors([
                'dispute' => 'You have already submitted a dispute for this order.',
            ]);
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
            'refund_requested_taka' => ['required', 'numeric', 'min:0'],
        ]);

        Dispute::create([
            'order_id' => $order->id,
            'group_cart_id' => $order->group_cart_id,
            'user_id' => $user->id,
            'vendor_user_id' => $order->vendor_user_id,
            'reason' => $validated['reason'],
            'refund_requested_paisa' => (int) round($validated['refund_requested_taka'] * 100),
            'status' => Dispute::STATUS_OPEN,
        ]);

        return redirect()
            ->route('disputes.index')
            ->with('status', 'dispute-submitted');
    }
}