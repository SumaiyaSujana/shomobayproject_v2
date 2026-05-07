<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Group Cart Details
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'group-cart-created')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Group cart created successfully.
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $groupCart->title }}
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Item:
                            <strong>{{ $groupCart->groceryItem->name }}</strong>
                        </p>

                        <p class="mt-1 text-gray-600">
                            Building:
                            <strong>{{ $groupCart->apartment_building }}</strong>
                        </p>

                        <p class="mt-1 text-gray-600">
                            Created by:
                            <strong>{{ $groupCart->creator?->name ?? 'Unknown' }}</strong>
                        </p>
                    </div>

                    <a
                        href="{{ route('group-carts.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Group Carts
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Current Weight</h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        {{ number_format($groupCart->current_weight_grams / 1000, 2) }} kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Target Weight</h4>

                    <p class="mt-3 text-3xl font-bold text-gray-900">
                        {{ number_format($groupCart->target_weight_grams / 1000, 2) }} kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Current Price / kg</h4>

                    <p class="mt-3 text-3xl font-bold text-indigo-700">
                        ৳{{ number_format($currentPricePerKgPaisa / 100, 2) }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Deadline</h4>

                    <p class="mt-3 text-lg font-bold text-orange-600">
                        {{ $groupCart->deadline_at->format('d M Y') }}
                    </p>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ $groupCart->deadline_at->format('h:i A') }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>
                        Progress toward wholesale threshold
                    </span>

                    <span>
                        {{ $progressPercentage }}%
                    </span>
                </div>

                <div class="mt-2 w-full bg-gray-200 rounded-full h-4">
                    <div
                        class="bg-green-600 h-4 rounded-full"
                        style="width: {{ $progressPercentage }}%"
                    ></div>
                </div>

                @if($canCheckout)
                    <p class="mt-4 text-green-700 font-semibold">
                        Wholesale threshold reached. Checkout will be added in a later step.
                    </p>
                @else
                    <p class="mt-4 text-orange-600 font-semibold">
                        Checkout locked until the group reaches the target weight.
                    </p>
                @endif
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Price Drop Information
                </h4>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">
                            Market Price / kg
                        </p>

                        <p class="mt-1 text-xl font-bold text-red-600">
                            ৳{{ number_format($groupCart->groceryItem->market_price_per_kg_paisa / 100, 2) }}
                        </p>
                    </div>

                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">
                            Current Price / kg
                        </p>

                        <p class="mt-1 text-xl font-bold text-indigo-700">
                            ৳{{ number_format($currentPricePerKgPaisa / 100, 2) }}
                        </p>
                    </div>

                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="text-sm text-gray-500">
                            Wholesale Price / kg
                        </p>

                        <p class="mt-1 text-xl font-bold text-green-700">
                            ৳{{ number_format($groupCart->groceryItem->wholesale_price_per_kg_paisa / 100, 2) }}
                        </p>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-600">
                    As more neighbors join, the price gradually drops from market price toward wholesale price.
                </p>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Neighbor Contributions
                </h4>

                @if($groupCart->contributions->isEmpty())
                    <p class="mt-3 text-gray-600">
                        No neighbors have contributed yet. Contribution form will be added in the next step.
                    </p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Neighbor
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quantity
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Estimated Amount
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach($groupCart->contributions as $contribution)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $contribution->user?->name ?? 'Unknown' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ number_format($contribution->quantity_grams / 1000, 2) }} kg
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            ৳{{ number_format($contribution->estimated_amount_paisa / 100, 2) }}
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