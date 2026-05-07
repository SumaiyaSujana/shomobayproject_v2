<?php

use App\Http\Controllers\AdminDisputeController;
use App\Http\Controllers\AdminVendorController;
use App\Http\Controllers\BidAcceptanceController;
use App\Http\Controllers\CartContributionController;
use App\Http\Controllers\ClaimTokenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryCoordinatorController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\GroupCartController;
use App\Http\Controllers\NeighborProfileController;
use App\Http\Controllers\OrderDeliveryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SubstitutionRequestController;
use App\Http\Controllers\SubstitutionVoteController;
use App\Http\Controllers\VendorAnalyticsController;
use App\Http\Controllers\VendorBidController;
use App\Http\Controllers\VendorOrderController;
use App\Http\Controllers\VendorProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminSystemHealthController;
use App\Http\Controllers\SeasonalityAlertController;
use App\Http\Controllers\VendorRouteOptimizationController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])
        ->name('wallet.top-up');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/neighbor/profile', [NeighborProfileController::class, 'edit'])
        ->name('neighbor.profile.edit');

    Route::patch('/neighbor/profile', [NeighborProfileController::class, 'update'])
        ->name('neighbor.profile.update');

    Route::get('/vendor/profile', [VendorProfileController::class, 'edit'])
        ->name('vendor.profile.edit');

    Route::patch('/vendor/profile', [VendorProfileController::class, 'update'])
        ->name('vendor.profile.update');

    Route::get('/admin/vendors', [AdminVendorController::class, 'index'])
        ->name('admin.vendors.index');

    Route::patch('/admin/vendors/{vendorProfile}/approve', [AdminVendorController::class, 'approve'])
        ->name('admin.vendors.approve');

    Route::patch('/admin/vendors/{vendorProfile}/mark-pending', [AdminVendorController::class, 'markPending'])
        ->name('admin.vendors.mark-pending');

    Route::get('/admin/disputes', [AdminDisputeController::class, 'index'])
        ->name('admin.disputes.index');

    Route::patch('/admin/disputes/{dispute}', [AdminDisputeController::class, 'update'])
        ->name('admin.disputes.update');

    Route::get('/admin/system-health', [AdminSystemHealthController::class, 'index'])
        ->name('admin.system-health.index');

    Route::get('/disputes', [DisputeController::class, 'index'])
        ->name('disputes.index');

    Route::post('/orders/{order}/disputes', [DisputeController::class, 'store'])
        ->name('orders.disputes.store');

    Route::get('/group-carts', [GroupCartController::class, 'index'])
        ->name('group-carts.index');

    Route::get('/group-carts/create', [GroupCartController::class, 'create'])
        ->name('group-carts.create');

    Route::post('/group-carts', [GroupCartController::class, 'store'])
        ->name('group-carts.store');

    Route::get('/group-carts/{groupCart}', [GroupCartController::class, 'show'])
        ->name('group-carts.show');

    Route::post('/group-carts/{groupCart}/contributions', [CartContributionController::class, 'store'])
        ->name('group-carts.contributions.store');

    Route::delete('/group-carts/{groupCart}/contributions', [CartContributionController::class, 'destroy'])
        ->name('group-carts.contributions.destroy');

    Route::patch('/group-carts/{groupCart}/bids/{bid}/accept', [BidAcceptanceController::class, 'accept'])
        ->name('group-carts.bids.accept');

    Route::patch('/group-carts/{groupCart}/expire-refund', [RefundController::class, 'expireGroupCart'])
        ->name('group-carts.expire-refund');

    Route::patch('/orders/{order}/refund', [RefundController::class, 'refundOrder'])
        ->name('orders.refund');

    Route::patch('/orders/{order}/mark-delivered', [OrderDeliveryController::class, 'markDelivered'])
        ->name('orders.mark-delivered');

    Route::patch('/orders/{order}/assign-coordinator', [DeliveryCoordinatorController::class, 'assign'])
        ->name('orders.assign-coordinator');

    Route::post('/orders/{order}/ratings', [RatingController::class, 'store'])
        ->name('orders.ratings.store');

    Route::get('/claim-tokens', [ClaimTokenController::class, 'index'])
        ->name('claim-tokens.index');

    Route::get('/claim-tokens/{cartContribution}', [ClaimTokenController::class, 'show'])
        ->name('claim-tokens.show');

    Route::patch('/claim-tokens/{cartContribution}/claim', [ClaimTokenController::class, 'claim'])
        ->name('claim-tokens.claim');

    Route::get('/vendor/bulk-requests', [VendorBidController::class, 'index'])
        ->name('vendor.bulk-requests.index');

    Route::get('/vendor/bulk-requests/{groupCart}', [VendorBidController::class, 'show'])
        ->name('vendor.bulk-requests.show');

    Route::post('/vendor/bulk-requests/{groupCart}/bids', [VendorBidController::class, 'store'])
        ->name('vendor.bulk-requests.bids.store');

    Route::get('/vendor/orders', [VendorOrderController::class, 'index'])
        ->name('vendor.orders.index');

    Route::post('/vendor/orders/{order}/substitution', [SubstitutionRequestController::class, 'store'])
        ->name('vendor.orders.substitution.store');

    Route::post('/substitution-requests/{substitutionRequest}/vote', [SubstitutionVoteController::class, 'vote'])
        ->name('substitution-requests.vote');

    Route::get('/vendor/analytics', [VendorAnalyticsController::class, 'index'])
        ->name('vendor.analytics.index');

    Route::get('/seasonality-alerts', [SeasonalityAlertController::class, 'index'])
        ->name('seasonality-alerts.index');

    Route::get('/vendor/route-optimization', [VendorRouteOptimizationController::class, 'index'])
        ->name('vendor.route-optimization.index');    
});

require __DIR__ . '/auth.php';