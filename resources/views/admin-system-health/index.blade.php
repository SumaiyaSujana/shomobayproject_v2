<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin System Health
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Shomobay System Health Tracker
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Monitor users, carts, orders, wallet movement, disputes, ratings, and substitution activity.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="rounded-lg p-6 border
                @if($healthStatus['color'] === 'green') bg-green-50 border-green-200
                @elseif($healthStatus['color'] === 'yellow') bg-yellow-50 border-yellow-200
                @else bg-red-50 border-red-200
                @endif
            ">
                <h4 class="text-lg font-bold
                    @if($healthStatus['color'] === 'green') text-green-900
                    @elseif($healthStatus['color'] === 'yellow') text-yellow-900
                    @else text-red-900
                    @endif
                ">
                    Overall Status: {{ $healthStatus['label'] }}
                </h4>

                <p class="mt-2
                    @if($healthStatus['color'] === 'green') text-green-700
                    @elseif($healthStatus['color'] === 'yellow') text-yellow-700
                    @else text-red-700
                    @endif
                ">
                    {{ $healthStatus['message'] }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Total Users</h4>
                    <p class="mt-3 text-3xl font-bold text-indigo-700">{{ $totalUsers }}</p>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $totalNeighbors }} neighbors, {{ $totalVendors }} vendors, {{ $totalAdmins }} admins
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Pending Vendor Approvals</h4>
                    <p class="mt-3 text-3xl font-bold text-orange-600">{{ $pendingVendorApprovals }}</p>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $verifiedVendors }} verified vendors
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Open Disputes</h4>
                    <p class="mt-3 text-3xl font-bold text-red-600">{{ $openDisputes }}</p>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $resolvedDisputes }} resolved, {{ $rejectedDisputes }} rejected
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Average Rating</h4>
                    <p class="mt-3 text-3xl font-bold text-green-700">
                        {{ $averageRating ? number_format($averageRating, 1) : '0.0' }}/5
                    </p>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $totalRatings }} total rating(s)
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Group Cart Status
                    </h4>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm text-green-700">Active</p>
                            <p class="mt-1 text-2xl font-bold text-green-900">{{ $activeCarts }}</p>
                        </div>

                        <div class="rounded-md bg-indigo-50 p-4">
                            <p class="text-sm text-indigo-700">Threshold Met</p>
                            <p class="mt-1 text-2xl font-bold text-indigo-900">{{ $thresholdMetCarts }}</p>
                        </div>

                        <div class="rounded-md bg-purple-50 p-4">
                            <p class="text-sm text-purple-700">Ordered</p>
                            <p class="mt-1 text-2xl font-bold text-purple-900">{{ $orderedCarts }}</p>
                        </div>

                        <div class="rounded-md bg-red-50 p-4">
                            <p class="text-sm text-red-700">Expired</p>
                            <p class="mt-1 text-2xl font-bold text-red-900">{{ $expiredCarts }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Order Status
                    </h4>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-md bg-orange-50 p-4">
                            <p class="text-sm text-orange-700">Escrow Held</p>
                            <p class="mt-1 text-2xl font-bold text-orange-900">{{ $escrowHeldOrders }}</p>
                        </div>

                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm text-green-700">Delivered</p>
                            <p class="mt-1 text-2xl font-bold text-green-900">{{ $deliveredOrders }}</p>
                        </div>

                        <div class="rounded-md bg-yellow-50 p-4">
                            <p class="text-sm text-yellow-700">Refunded</p>
                            <p class="mt-1 text-2xl font-bold text-yellow-900">{{ $refundedOrders }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Bid and Substitution Activity
                    </h4>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-md bg-yellow-50 p-4">
                            <p class="text-sm text-yellow-700">Pending Bids</p>
                            <p class="mt-1 text-2xl font-bold text-yellow-900">{{ $pendingBids }}</p>
                        </div>

                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm text-green-700">Accepted Bids</p>
                            <p class="mt-1 text-2xl font-bold text-green-900">{{ $acceptedBids }}</p>
                        </div>

                        <div class="rounded-md bg-red-50 p-4">
                            <p class="text-sm text-red-700">Rejected Bids</p>
                            <p class="mt-1 text-2xl font-bold text-red-900">{{ $rejectedBids }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-md bg-purple-50 p-4">
                            <p class="text-sm text-purple-700">Pending Substitutions</p>
                            <p class="mt-1 text-2xl font-bold text-purple-900">{{ $pendingSubstitutions }}</p>
                        </div>

                        <div class="rounded-md bg-green-50 p-4">
                            <p class="text-sm text-green-700">Approved</p>
                            <p class="mt-1 text-2xl font-bold text-green-900">{{ $approvedSubstitutions }}</p>
                        </div>

                        <div class="rounded-md bg-red-50 p-4">
                            <p class="text-sm text-red-700">Rejected</p>
                            <p class="mt-1 text-2xl font-bold text-red-900">{{ $rejectedSubstitutions }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Wallet and Escrow Summary
                    </h4>

                    <div class="mt-4 space-y-4">
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Total Wallet Balance</p>
                            <p class="mt-1 text-2xl font-bold text-green-700">
                                ৳{{ number_format($totalWalletBalancePaisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Total Escrow Held</p>
                            <p class="mt-1 text-2xl font-bold text-orange-600">
                                ৳{{ number_format($totalEscrowPaisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Total Order Value</p>
                            <p class="mt-1 text-2xl font-bold text-indigo-700">
                                ৳{{ number_format($totalOrderAmountPaisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Vendor Payments Released</p>
                            <p class="mt-1 text-2xl font-bold text-green-700">
                                ৳{{ number_format($totalVendorPaymentsPaisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Escrow Refunds</p>
                            <p class="mt-1 text-2xl font-bold text-red-600">
                                ৳{{ number_format($totalRefundsPaisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Coordinator Discounts</p>
                            <p class="mt-1 text-2xl font-bold text-purple-700">
                                ৳{{ number_format($totalCoordinatorDiscountPaisa / 100, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Recent Orders
                    </h4>

                    @if($recentOrders->isEmpty())
                        <p class="mt-3 text-gray-600">
                            No orders yet.
                        </p>
                    @else
                        <div class="mt-4 space-y-3">
                            @foreach($recentOrders as $order)
                                <div class="rounded-md border border-gray-200 p-4">
                                    <div class="flex justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                Order #{{ $order->id }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ $order->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Vendor:
                                                {{ $order->vendor?->vendorProfile?->business_name ?? $order->vendor?->name ?? 'Vendor' }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                            </p>

                                            <p class="mt-1 font-bold text-green-700">
                                                ৳{{ number_format($order->total_amount_paisa / 100, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Recent Disputes
                    </h4>

                    @if($recentDisputes->isEmpty())
                        <p class="mt-3 text-gray-600">
                            No disputes yet.
                        </p>
                    @else
                        <div class="mt-4 space-y-3">
                            @foreach($recentDisputes as $dispute)
                                <div class="rounded-md border border-gray-200 p-4">
                                    <div class="flex justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                Dispute #{{ $dispute->id }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ $dispute->order?->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                By: {{ $dispute->user?->name ?? 'Neighbor' }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                {{ ucfirst($dispute->status) }}
                                            </p>

                                            <p class="mt-1 font-bold text-red-600">
                                                ৳{{ number_format($dispute->refund_requested_paisa / 100, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <a
                            href="{{ route('admin.disputes.index') }}"
                            class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-900 underline"
                        >
                            Open dispute dashboard
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>