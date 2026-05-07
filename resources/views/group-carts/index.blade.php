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
                            Nearby Group Carts and Orders
                        </h3>

                        <p class="mt-2 text-gray-600">
                            You can see active carts, threshold-met carts, and ordered carts from your own building or within a 1 km neighborhood radius.
                        </p>

                        @if($neighborProfile?->apartment_building)
                            <p class="mt-2 text-sm text-gray-500">
                                Your building:
                                <strong>{{ $neighborProfile->apartment_building }}</strong>
                            </p>
                        @endif

                        @if($neighborProfile?->location_coordinates)
                            <p class="mt-1 text-sm text-gray-500">
                                Your coordinates:
                                <strong>{{ $neighborProfile->location_coordinates }}</strong>
                            </p>
                        @else
                            <p class="mt-1 text-sm text-orange-600">
                                Add your coordinates in your profile to enable accurate 1 km filtering.
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
                        No nearby group carts or ordered carts found. You can start one for your building.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($groupCarts as $cart)
                        @php
                            $currentPrice = $pricingService->currentPricePerKgPaisa($cart);
                            $progress = $pricingService->progressPercentage($cart);
                            $sameBuilding = $neighborProfile?->apartment_building === $cart->apartment_building;
                            $distanceText = $geoDistanceService->formattedDistance(
                                $neighborProfile?->location_coordinates,
                                $cart->location_coordinates
                            );
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

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $distanceText }}
                                    </p>

                                    @if($cart->order?->substitutionRequest)
                                        <p class="mt-2 text-sm font-semibold text-purple-700">
                                            Substitution voting available
                                        </p>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 items-end">
                                    @if($cart->status === \App\Models\GroupCart::STATUS_ORDERED)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Ordered
                                        </span>
                                    @elseif($cart->status === \App\Models\GroupCart::STATUS_THRESHOLD_MET)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Threshold Met
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @endif

                                    @if($sameBuilding)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Same Building
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Within 1 km
                                        </span>
                                    @endif
                                </div>
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
                                    @if($cart->status === \App\Models\GroupCart::STATUS_ORDERED)
                                        View Order
                                    @else
                                        View and Join
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>