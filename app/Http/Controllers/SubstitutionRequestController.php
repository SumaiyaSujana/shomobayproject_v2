<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SubstitutionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubstitutionRequestController extends Controller
{
    /**
     * Vendor proposes a substitute item for an escrow-held order.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        if (!$user->vendorProfile?->is_verified) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'vendor-not-verified');
        }

        $order->load(['groupCart.groceryItem', 'substitutionRequest']);

        if ($order->vendor_user_id !== $user->id) {
            return back()->withErrors([
                'substitution' => 'You can only propose substitutions for your own accepted orders.',
            ]);
        }

        if ($order->status !== Order::STATUS_ESCROW_HELD) {
            return back()->withErrors([
                'substitution' => 'Substitution can only be proposed before delivery or refund.',
            ]);
        }

        if ($order->substitutionRequest) {
            return back()->withErrors([
                'substitution' => 'A substitution request already exists for this order.',
            ]);
        }

        $validated = $request->validate([
            'substitute_item_name' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        SubstitutionRequest::create([
            'order_id' => $order->id,
            'group_cart_id' => $order->group_cart_id,
            'vendor_user_id' => $user->id,
            'original_item_name' => $order->groupCart->groceryItem->name,
            'substitute_item_name' => $validated['substitute_item_name'],
            'reason' => $validated['reason'] ?? null,
            'status' => SubstitutionRequest::STATUS_PENDING,
        ]);

        return redirect()
            ->route('vendor.orders.index')
            ->with('status', 'substitution-created');
    }
}