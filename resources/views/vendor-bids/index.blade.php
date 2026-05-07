<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vendor Bulk Requests
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Threshold-Met Group Carts
                        </h3>

                        <p class="mt-2 text-gray-600">
                            These carts have reached the wholesale threshold. Verified vendors can now submit supply bids.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Dashboard
                    </a>
                </div>
            </div>

            @if($groupCarts->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        No threshold-met bulk requests are available right now.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($groupCarts as $cart)
                        @php
                            $vendorBid = $cart->bids->firstWhere('vendor_user_id', auth()->id());
                            $estimatedTotalPaisa = $vendorBid
                                ? $vendorBid->estimatedTotalPaisa($cart->current_weight_grams)
                                : null;
                        @endphp

                        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">
                                        {{ $cart->title }}
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Item: {{ $cart->groceryItem->name }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Building: {{ $cart->apartment_building }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Required Quantity:
                                        {{ number_format($cart->current_weight_grams / 1000, 2) }} kg
                                    </p>
                                </div>

                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    Ready for Bid
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Wholesale Price Reference
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-green-700">
                                        ৳{{ number_format($cart->groceryItem->wholesale_price_per_kg_paisa / 100, 2) }}/kg
                                    </p>
                                </div>

                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Your Bid
                                    </p>

                                    @if($vendorBid)
                                        <p class="mt-1 text-xl font-bold text-indigo-700">
                                            ৳{{ number_format($vendorBid->price_per_kg_paisa / 100, 2) }}/kg
                                        </p>

                                        <p class="mt-1 text-sm text-gray-600">
                                            Est. total: ৳{{ number_format($estimatedTotalPaisa / 100, 2) }}
                                        </p>
                                    @else
                                        <p class="mt-1 text-sm font-semibold text-orange-600">
                                            Not submitted yet
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5">
                                <a
                                    href="{{ route('vendor.bulk-requests.show', $cart) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                >
                                    View and Bid
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>