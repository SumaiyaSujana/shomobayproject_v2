<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\GroupCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorBidController extends Controller
{
    /**
     * Show threshold-met group carts that verified vendors can bid on.
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        if (!$user->vendorProfile?->is_verified) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'vendor-not-verified');
        }

        $groupCarts = GroupCart::with([
                'groceryItem',
                'creator',
                'contributions',
                'bids' => function ($query) use ($user) {
                    $query->where('vendor_user_id', $user->id);
                },
            ])
            ->where('status', GroupCart::STATUS_THRESHOLD_MET)
            ->latest()
            ->get();

        return view('vendor-bids.index', [
            'groupCarts' => $groupCarts,
        ]);
    }

    /**
     * Show one bulk request and bid form.
     */
    public function show(GroupCart $groupCart): View|RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        if (!$user->vendorProfile?->is_verified) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'vendor-not-verified');
        }

        $groupCart->load(['groceryItem', 'creator', 'contributions.user', 'bids.vendor']);

        $currentVendorBid = Bid::where('group_cart_id', $groupCart->id)
            ->where('vendor_user_id', $user->id)
            ->first();

        return view('vendor-bids.show', [
            'groupCart' => $groupCart,
            'currentVendorBid' => $currentVendorBid,
        ]);
    }

    /**
     * Store or update vendor bid.
     */
    public function store(Request $request, GroupCart $groupCart): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        if (!$user->vendorProfile?->is_verified) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'vendor-not-verified');
        }

        if ($groupCart->status !== GroupCart::STATUS_THRESHOLD_MET) {
            return back()->withErrors([
                'price_per_kg_taka' => 'This cart is not ready for bidding yet. The wholesale threshold must be reached first.',
            ]);
        }

        $validated = $request->validate([
            'price_per_kg_taka' => ['required', 'numeric', 'min:1'],
            'delivery_fee_taka' => ['nullable', 'numeric', 'min:0'],
            'estimated_delivery_at' => ['nullable', 'date', 'after:now'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        Bid::updateOrCreate(
            [
                'group_cart_id' => $groupCart->id,
                'vendor_user_id' => $user->id,
            ],
            [
                'price_per_kg_paisa' => (int) round($validated['price_per_kg_taka'] * 100),
                'delivery_fee_paisa' => (int) round(($validated['delivery_fee_taka'] ?? 0) * 100),
                'estimated_delivery_at' => $validated['estimated_delivery_at'] ?? null,
                'note' => $validated['note'] ?? null,
                'status' => Bid::STATUS_PENDING,
            ]
        );

        return redirect()
            ->route('vendor.bulk-requests.show', $groupCart)
            ->with('status', 'bid-saved');
    }
}