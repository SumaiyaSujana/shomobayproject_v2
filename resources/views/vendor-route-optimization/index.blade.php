<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Delivery Route Optimization
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Optimized Delivery Route
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Suggested delivery sequence for escrow-held orders. This demo sorts stops using nearby coordinates.
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

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Total Stops
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-indigo-700">
                        {{ $summary['total_orders'] }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Buildings
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-purple-700">
                        {{ $summary['total_buildings'] }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Total Weight
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        {{ number_format($summary['total_weight_kg'], 2) }} kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Estimated Distance
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-orange-600">
                        {{ number_format($summary['estimated_route_distance_km'], 2) }} km
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Route Value
                </h4>

                <p class="mt-3 text-3xl font-bold text-green-700">
                    ৳{{ number_format($summary['total_order_value_paisa'] / 100, 2) }}
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Total value of escrow-held orders in this route.
                </p>
            </div>

            @if($stops->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        No escrow-held orders are waiting for delivery route planning.
                    </p>
                </div>
            @else
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Suggested Stop Sequence
                    </h4>

                    <div class="mt-6 space-y-4">
                        @foreach($stops as $stop)
                            <div class="rounded-md border border-gray-200 p-4">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="flex gap-4">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-700 text-white font-bold">
                                            {{ $stop['sequence'] }}
                                        </div>

                                        <div>
                                            <h5 class="font-bold text-gray-900">
                                                Order #{{ $stop['order']->id }}
                                            </h5>

                                            <p class="mt-1 text-sm text-gray-600">
                                                Cart:
                                                {{ $stop['cart']?->title ?? 'Unknown Cart' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                Item:
                                                {{ $stop['cart']?->groceryItem?->name ?? 'Unknown Item' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                Building:
                                                {{ $stop['cart']?->apartment_building ?? 'Not added' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Coordinates:
                                                {{ $stop['cart']?->location_coordinates ?? 'Not added' }}
                                            </p>

                                            @if($stop['order']->substitutionRequest)
                                                <p class="mt-2 text-sm font-semibold text-purple-700">
                                                    Substitution:
                                                    {{ ucfirst($stop['order']->substitutionRequest->status) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">
                                            Distance From Previous Stop
                                        </p>

                                        <p class="font-bold text-orange-600">
                                            {{ number_format($stop['distance_from_previous_km'] ?? 0, 2) }} km
                                        </p>

                                        <p class="mt-2 text-sm text-gray-500">
                                            Weight
                                        </p>

                                        <p class="font-bold text-green-700">
                                            {{ number_format(($stop['cart']?->current_weight_grams ?? 0) / 1000, 2) }} kg
                                        </p>

                                        <p class="mt-2 text-sm text-gray-500">
                                            Order Value
                                        </p>

                                        <p class="font-bold text-indigo-700">
                                            ৳{{ number_format($stop['order']->total_amount_paisa / 100, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>