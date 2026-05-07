<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Neighborhood Group Carts
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Active Group Carts
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Join your neighbors to reach wholesale quantity and unlock a lower price.
                        </p>

                        @if($neighborProfile?->apartment_building)
                            <p class="mt-2 text-sm text-gray-500">
                                Your building:
                                <strong>{{ $neighborProfile->apartment_building }}</strong>
                            </p>
                        @endif
                    </div>

                    <a
                        href="{{ route('group-carts.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                    >
                        Create Group Cart
                    </a>
                </div>
            </div>

            @if($groupCarts->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        No active group carts yet. Be the first neighbor to start one.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($groupCarts as $cart)
                        @php
                            $currentPrice = $pricingService->currentPricePerKgPaisa($cart);
                            $progress = $pricingService->progressPercentage($cart);
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
                                </div>

                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>

                            <div class="mt-5">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>
                                        {{ number_format($cart->current_weight_grams / 1000, 2) }} kg joined
                                    </span>

                                    <span>
                                        Target: {{ number_format($cart->target_weight_grams / 1000, 2) }} kg
                                    </span>
                                </div>

                                <div class="mt-2 w-full bg-gray-200 rounded-full h-3">
                                    <div
                                        class="bg-green-600 h-3 rounded-full"
                                        style="width: {{ $progress }}%"
                                    ></div>
                                </div>

                                <p class="mt-2 text-sm text-gray-500">
                                    Progress: {{ $progress }}%
                                </p>
                            </div>

                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Current Price / kg
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-green-700">
                                        ৳{{ number_format($currentPrice / 100, 2) }}
                                    </p>
                                </div>

                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Deadline
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800">
                                        {{ $cart->deadline_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <a
                                    href="{{ route('group-carts.show', $cart) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                >
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>