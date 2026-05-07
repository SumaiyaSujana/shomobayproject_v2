<?php

namespace App\Http\Controllers;

use App\Models\GroceryItem;
use App\Models\GroupCart;
use App\Services\GeoDistanceService;
use App\Services\GroupCartPricingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GroupCartController extends Controller
{
    /**
     * Show active and threshold-met group carts for nearby neighbors.
     */
    public function index(
        GroupCartPricingService $pricingService,
        GeoDistanceService $geoDistanceService
    ): View {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $neighborProfile = $user->neighborProfile;

        $allGroupCarts = GroupCart::with(['groceryItem', 'creator'])
            ->whereIn('status', [
                GroupCart::STATUS_ACTIVE,
                GroupCart::STATUS_THRESHOLD_MET,
            ])
            ->latest()
            ->get();

        $groupCarts = $allGroupCarts->filter(function (GroupCart $cart) use ($neighborProfile, $geoDistanceService) {
            if (!$neighborProfile) {
                return false;
            }

            $sameBuilding = $neighborProfile->apartment_building
                && $cart->apartment_building === $neighborProfile->apartment_building;

            $withinOneKm = $geoDistanceService->isWithinOneKm(
                $neighborProfile->location_coordinates,
                $cart->location_coordinates
            );

            return $sameBuilding || $withinOneKm;
        });

        return view('group-carts.index', [
            'groupCarts' => $groupCarts,
            'neighborProfile' => $neighborProfile,
            'pricingService' => $pricingService,
            'geoDistanceService' => $geoDistanceService,
        ]);
    }

    /**
     * Show create group cart form.
     */
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $neighborProfile = $user->neighborProfile;

        if (!$neighborProfile || !$neighborProfile->apartment_building) {
            return redirect()
                ->route('neighbor.profile.edit')
                ->withErrors([
                    'apartment_building' => 'Please add your apartment building before creating a group cart.',
                ]);
        }

        $groceryItems = GroceryItem::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('group-carts.create', [
            'groceryItems' => $groceryItems,
            'neighborProfile' => $neighborProfile,
        ]);
    }

    /**
     * Store a new group cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $neighborProfile = $user->neighborProfile;

        if (!$neighborProfile || !$neighborProfile->apartment_building) {
            return redirect()
                ->route('neighbor.profile.edit')
                ->withErrors([
                    'apartment_building' => 'Please add your apartment building before creating a group cart.',
                ]);
        }

        $validated = $request->validate([
            'grocery_item_id' => ['required', 'exists:grocery_items,id'],
            'title' => ['required', 'string', 'max:255'],
            'target_weight_kg' => ['required', 'numeric', 'min:1'],
            'deadline_at' => ['required', 'date', 'after:now'],
        ]);

        $groceryItem = GroceryItem::findOrFail($validated['grocery_item_id']);

        $targetWeightGrams = (int) round($validated['target_weight_kg'] * 1000);

        if ($targetWeightGrams < $groceryItem->minimum_bulk_weight_grams) {
            return back()
                ->withInput()
                ->withErrors([
                    'target_weight_kg' => 'Target weight must be at least '
                        . number_format($groceryItem->minimum_bulk_weight_grams / 1000, 2)
                        . ' kg for this item.',
                ]);
        }

        $groupCart = GroupCart::create([
            'created_by_user_id' => $user->id,
            'grocery_item_id' => $groceryItem->id,
            'title' => $validated['title'],
            'apartment_building' => $neighborProfile->apartment_building,
            'location_coordinates' => $neighborProfile->location_coordinates,
            'target_weight_grams' => $targetWeightGrams,
            'current_weight_grams' => 0,
            'deadline_at' => Carbon::parse($validated['deadline_at']),
            'status' => GroupCart::STATUS_ACTIVE,
        ]);

        return redirect()
            ->route('group-carts.show', $groupCart)
            ->with('status', 'group-cart-created');
    }

    /**
     * Show group cart details.
     */
    public function show(GroupCart $groupCart, GroupCartPricingService $pricingService): View
    {
        $user = Auth::user();

        abort_unless($user->isNeighbor(), 403);

        $groupCart->load([
            'groceryItem',
            'creator',
            'contributions.user.wallet',
            'bids.vendor.vendorProfile',
            'order.bid.vendor.vendorProfile',
        ]);

        $currentUserContribution = $groupCart->contributions
            ->firstWhere('user_id', $user->id);

        $sameBuilding = $user->neighborProfile
            && $user->neighborProfile->apartment_building === $groupCart->apartment_building;

        $canContribute = $sameBuilding
            && !$groupCart->deadline_at->isPast()
            && $groupCart->status !== GroupCart::STATUS_ORDERED
            && $groupCart->status !== GroupCart::STATUS_EXPIRED;

        $canAcceptBid = $user->id === $groupCart->created_by_user_id
            && $groupCart->status === GroupCart::STATUS_THRESHOLD_MET
            && !$groupCart->order;

        return view('group-carts.show', [
            'groupCart' => $groupCart,
            'pricingService' => $pricingService,
            'currentPricePerKgPaisa' => $pricingService->currentPricePerKgPaisa($groupCart),
            'progressPercentage' => $pricingService->progressPercentage($groupCart),
            'canCheckout' => $pricingService->canCheckout($groupCart),
            'remainingWeightGrams' => $pricingService->remainingWeightGrams($groupCart),
            'currentUserContribution' => $currentUserContribution,
            'canContribute' => $canContribute,
            'canAcceptBid' => $canAcceptBid,
            'minimumContributionKg' => $groupCart->groceryItem->minimum_contribution_grams / 1000,
        ]);
    }
}