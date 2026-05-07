<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\GroupCart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorAnalyticsController extends Controller
{
    /**
     * Show vendor revenue analytics.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->isVendor(), 403);

        $vendorProfile = $user->vendorProfile;
        $wallet = $user->wallet;

        $acceptedOrders = Order::with(['groupCart.groceryItem', 'bid'])
            ->where('vendor_user_id', $user->id)
            ->latest()
            ->get();

        $monthlyExpectedEarningsPaisa = Order::where('vendor_user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount_paisa');

        $totalExpectedEarningsPaisa = Order::where('vendor_user_id', $user->id)
            ->sum('total_amount_paisa');

        $activeBids = Bid::with(['groupCart.groceryItem'])
            ->where('vendor_user_id', $user->id)
            ->where('status', Bid::STATUS_PENDING)
            ->latest()
            ->get();

        $acceptedBidsCount = Bid::where('vendor_user_id', $user->id)
            ->where('status', Bid::STATUS_ACCEPTED)
            ->count();

        $rejectedBidsCount = Bid::where('vendor_user_id', $user->id)
            ->where('status', Bid::STATUS_REJECTED)
            ->count();

        $availableBulkRequests = GroupCart::where('status', GroupCart::STATUS_THRESHOLD_MET)
            ->count();

        $mostRequestedItems = GroupCart::query()
            ->selectRaw('grocery_item_id, COUNT(*) as cart_count, SUM(current_weight_grams) as total_weight_grams')
            ->with('groceryItem')
            ->whereIn('status', [
                GroupCart::STATUS_THRESHOLD_MET,
                GroupCart::STATUS_ORDERED,
            ])
            ->groupBy('grocery_item_id')
            ->orderByDesc('total_weight_grams')
            ->limit(5)
            ->get();

        return view('vendor-analytics.index', [
            'user' => $user,
            'vendorProfile' => $vendorProfile,
            'wallet' => $wallet,
            'acceptedOrders' => $acceptedOrders,
            'monthlyExpectedEarningsPaisa' => $monthlyExpectedEarningsPaisa,
            'totalExpectedEarningsPaisa' => $totalExpectedEarningsPaisa,
            'activeBids' => $activeBids,
            'acceptedBidsCount' => $acceptedBidsCount,
            'rejectedBidsCount' => $rejectedBidsCount,
            'availableBulkRequests' => $availableBulkRequests,
            'mostRequestedItems' => $mostRequestedItems,
        ]);
    }
}