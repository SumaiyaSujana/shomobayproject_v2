<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Dispute;
use App\Models\GroupCart;
use App\Models\Order;
use App\Models\Rating;
use App\Models\SubstitutionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminSystemHealthController extends Controller
{
    /**
     * Show admin system health dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->isAdmin(), 403);

        $totalUsers = User::count();
        $totalNeighbors = User::where('role', User::ROLE_NEIGHBOR)->count();
        $totalVendors = User::where('role', User::ROLE_VENDOR)->count();
        $totalAdmins = User::where('role', User::ROLE_ADMIN)->count();

        $pendingVendorApprovals = VendorProfile::where('is_verified', false)->count();
        $verifiedVendors = VendorProfile::where('is_verified', true)->count();

        $activeCarts = GroupCart::where('status', GroupCart::STATUS_ACTIVE)->count();
        $thresholdMetCarts = GroupCart::where('status', GroupCart::STATUS_THRESHOLD_MET)->count();
        $orderedCarts = GroupCart::where('status', GroupCart::STATUS_ORDERED)->count();
        $expiredCarts = GroupCart::where('status', GroupCart::STATUS_EXPIRED)->count();

        $escrowHeldOrders = Order::where('status', Order::STATUS_ESCROW_HELD)->count();
        $deliveredOrders = Order::where('status', Order::STATUS_DELIVERED)->count();
        $refundedOrders = Order::where('status', Order::STATUS_REFUNDED)->count();

        $pendingBids = Bid::where('status', Bid::STATUS_PENDING)->count();
        $acceptedBids = Bid::where('status', Bid::STATUS_ACCEPTED)->count();
        $rejectedBids = Bid::where('status', Bid::STATUS_REJECTED)->count();

        $openDisputes = Dispute::where('status', Dispute::STATUS_OPEN)->count();
        $resolvedDisputes = Dispute::where('status', Dispute::STATUS_RESOLVED)->count();
        $rejectedDisputes = Dispute::where('status', Dispute::STATUS_REJECTED)->count();

        $pendingSubstitutions = SubstitutionRequest::where('status', SubstitutionRequest::STATUS_PENDING)->count();
        $approvedSubstitutions = SubstitutionRequest::where('status', SubstitutionRequest::STATUS_APPROVED)->count();
        $rejectedSubstitutions = SubstitutionRequest::where('status', SubstitutionRequest::STATUS_REJECTED)->count();

        $totalWalletBalancePaisa = Wallet::sum('balance_paisa');
        $totalEscrowPaisa = Wallet::sum('escrow_paisa');
        $totalOrderAmountPaisa = Order::sum('total_amount_paisa');
        $totalVendorPaymentsPaisa = Transaction::where('type', 'vendor_payment')->sum('amount_paisa');
        $totalRefundsPaisa = Transaction::where('type', 'escrow_refund')->sum('amount_paisa');
        $totalCoordinatorDiscountPaisa = Transaction::where('type', 'coordinator_discount')->sum('amount_paisa');

        $averageRating = Rating::avg('score');
        $totalRatings = Rating::count();

        $recentOrders = Order::with(['groupCart.groceryItem', 'vendor.vendorProfile'])
            ->latest()
            ->limit(5)
            ->get();

        $recentDisputes = Dispute::with(['order.groupCart.groceryItem', 'user', 'vendor.vendorProfile'])
            ->latest()
            ->limit(5)
            ->get();

        $healthStatus = $this->calculateHealthStatus(
            $pendingVendorApprovals,
            $openDisputes,
            $escrowHeldOrders,
            $pendingSubstitutions
        );

        return view('admin-system-health.index', [
            'totalUsers' => $totalUsers,
            'totalNeighbors' => $totalNeighbors,
            'totalVendors' => $totalVendors,
            'totalAdmins' => $totalAdmins,
            'pendingVendorApprovals' => $pendingVendorApprovals,
            'verifiedVendors' => $verifiedVendors,
            'activeCarts' => $activeCarts,
            'thresholdMetCarts' => $thresholdMetCarts,
            'orderedCarts' => $orderedCarts,
            'expiredCarts' => $expiredCarts,
            'escrowHeldOrders' => $escrowHeldOrders,
            'deliveredOrders' => $deliveredOrders,
            'refundedOrders' => $refundedOrders,
            'pendingBids' => $pendingBids,
            'acceptedBids' => $acceptedBids,
            'rejectedBids' => $rejectedBids,
            'openDisputes' => $openDisputes,
            'resolvedDisputes' => $resolvedDisputes,
            'rejectedDisputes' => $rejectedDisputes,
            'pendingSubstitutions' => $pendingSubstitutions,
            'approvedSubstitutions' => $approvedSubstitutions,
            'rejectedSubstitutions' => $rejectedSubstitutions,
            'totalWalletBalancePaisa' => $totalWalletBalancePaisa,
            'totalEscrowPaisa' => $totalEscrowPaisa,
            'totalOrderAmountPaisa' => $totalOrderAmountPaisa,
            'totalVendorPaymentsPaisa' => $totalVendorPaymentsPaisa,
            'totalRefundsPaisa' => $totalRefundsPaisa,
            'totalCoordinatorDiscountPaisa' => $totalCoordinatorDiscountPaisa,
            'averageRating' => $averageRating,
            'totalRatings' => $totalRatings,
            'recentOrders' => $recentOrders,
            'recentDisputes' => $recentDisputes,
            'healthStatus' => $healthStatus,
        ]);
    }

    /**
     * Calculate simple operational health level.
     */
    private function calculateHealthStatus(
        int $pendingVendorApprovals,
        int $openDisputes,
        int $escrowHeldOrders,
        int $pendingSubstitutions
    ): array {
        $riskScore = 0;

        if ($pendingVendorApprovals > 5) {
            $riskScore++;
        }

        if ($openDisputes > 3) {
            $riskScore += 2;
        }

        if ($escrowHeldOrders > 10) {
            $riskScore++;
        }

        if ($pendingSubstitutions > 5) {
            $riskScore++;
        }

        if ($riskScore >= 3) {
            return [
                'label' => 'Needs Attention',
                'color' => 'red',
                'message' => 'Several operational items need admin review.',
            ];
        }

        if ($riskScore >= 1) {
            return [
                'label' => 'Stable with Warnings',
                'color' => 'yellow',
                'message' => 'System is running, but some admin actions are pending.',
            ];
        }

        return [
            'label' => 'Healthy',
            'color' => 'green',
            'message' => 'System activity looks stable.',
        ];
    }
}