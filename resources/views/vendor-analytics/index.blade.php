<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vendor Revenue Analytics
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Analytics for {{ $vendorProfile?->business_name ?? $user->name }}
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Track expected revenue, active bids, accepted orders, and high-demand grocery items.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ route('vendor.bulk-requests.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                        >
                            Browse Requests
                        </a>

                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                        >
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        This Month Expected Earnings
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        ৳{{ number_format($monthlyExpectedEarningsPaisa / 100, 2) }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        From accepted orders this month
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Total Expected Earnings
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-indigo-700">
                        ৳{{ number_format($totalExpectedEarningsPaisa / 100, 2) }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        All accepted orders
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Active Pending Bids
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-orange-600">
                        {{ $activeBids->count() }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Bids waiting for neighbor decision
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Accepted Bids
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        {{ $acceptedBidsCount }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Bids converted to orders
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Bid Performance
                    </h4>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">
                                Available Requests
                            </p>

                            <p class="mt-1 text-2xl font-bold text-indigo-700">
                                {{ $availableBulkRequests }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">
                                Accepted
                            </p>

                            <p class="mt-1 text-2xl font-bold text-green-700">
                                {{ $acceptedBidsCount }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">
                                Rejected
                            </p>

                            <p class="mt-1 text-2xl font-bold text-red-600">
                                {{ $rejectedBidsCount }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-gray-600">
                        This helps vendors understand whether their prices are competitive in neighborhood bulk buying.
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Most Requested Items
                    </h4>

                    @if($mostRequestedItems->isEmpty())
                        <p class="mt-3 text-gray-600">
                            No threshold-met or ordered carts yet.
                        </p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach($mostRequestedItems as $itemStat)
                                <div class="rounded-md border border-gray-200 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $itemStat->groceryItem?->name ?? 'Unknown Item' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ $itemStat->cart_count }} group cart(s)
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                Total Demand
                                            </p>

                                            <p class="font-bold text-green-700">
                                                {{ number_format(($itemStat->total_weight_grams ?? 0) / 1000, 2) }} kg
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Active Pending Bids
                </h4>

                @if($activeBids->isEmpty())
                    <p class="mt-3 text-gray-600">
                        You do not have any pending bids right now.
                    </p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Cart
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Item
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quantity
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Bid Price
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Estimated Total
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach($activeBids as $bid)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $bid->groupCart?->title ?? 'Unknown Cart' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $bid->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ number_format(($bid->groupCart?->current_weight_grams ?? 0) / 1000, 2) }} kg
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            ৳{{ number_format($bid->price_per_kg_paisa / 100, 2) }}/kg
                                        </td>

                                        <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                                            ৳{{ number_format($bid->estimatedTotalPaisa($bid->groupCart?->current_weight_grams ?? 0) / 100, 2) }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-right">
                                            @if($bid->groupCart)
                                                <a
                                                    href="{{ route('vendor.bulk-requests.show', $bid->groupCart) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 underline"
                                                >
                                                    View
                                                </a>
                                            @else
                                                <span class="text-gray-500">
                                                    Unavailable
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Accepted Orders
                </h4>

                @if($acceptedOrders->isEmpty())
                    <p class="mt-3 text-gray-600">
                        No accepted orders yet.
                    </p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Order
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Item
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quantity
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Total
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Status
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach($acceptedOrders as $order)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            #{{ $order->id }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $order->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ number_format(($order->groupCart?->current_weight_grams ?? 0) / 1000, 2) }} kg
                                        </td>

                                        <td class="px-4 py-4 text-sm font-semibold text-green-700">
                                            ৳{{ number_format($order->total_amount_paisa / 100, 2) }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>