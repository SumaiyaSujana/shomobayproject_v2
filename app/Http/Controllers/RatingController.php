<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a produce quality rating for a delivered order.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $order->load('groupCart.contributions');

        if ($order->status !== Order::STATUS_DELIVERED) {
            return back()->withErrors([
                'rating' => 'You can only rate an order after it is marked as delivered.',
            ]);
        }

        $hasContribution = $order->groupCart->contributions
            ->contains('user_id', $user->id);

        if (!$hasContribution) {
            return back()->withErrors([
                'rating' => 'Only neighbors who contributed to this cart can rate the delivered produce.',
            ]);
        }

        $alreadyRated = Rating::where('order_id', $order->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRated) {
            return back()->withErrors([
                'rating' => 'You have already rated this delivered order.',
            ]);
        }

        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Rating::create([
            'order_id' => $order->id,
            'group_cart_id' => $order->group_cart_id,
            'user_id' => $user->id,
            'vendor_user_id' => $order->vendor_user_id,
            'score' => $validated['score'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('group-carts.show', $order->groupCart)
            ->with('status', 'rating-submitted');
    }
}