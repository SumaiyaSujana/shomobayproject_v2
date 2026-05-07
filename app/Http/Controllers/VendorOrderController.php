<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorOrderController extends Controller
{
    /**
     * Show accepted vendor orders and substitution tools.
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

        $orders = Order::with([
                'groupCart.groceryItem',
                'groupCart.contributions.user',
                'substitutionRequest.votes.user',
            ])
            ->where('vendor_user_id', $user->id)
            ->latest()
            ->get();

        return view('vendor-orders.index', [
            'orders' => $orders,
        ]);
    }
}