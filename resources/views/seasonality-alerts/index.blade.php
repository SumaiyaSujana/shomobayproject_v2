<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Seasonality Alerts
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Grocery Seasonality Alerts
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Use seasonal signals to decide which grocery items are better for group buying and vendor bidding.
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

            @if($alerts->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        No active grocery items were found for seasonality alerts.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($alerts as $alert)
                        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900">
                                        {{ $alert['item']->name }}
                                    </h4>

                                    <p class="mt-2 text-sm text-gray-600">
                                        Seasonal window:
                                        <strong>{{ $alert['season_months'] }}</strong>
                                    </p>
                                </div>

                                @if($alert['badge_color'] === 'green')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $alert['status'] }}
                                    </span>
                                @elseif($alert['badge_color'] === 'yellow')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $alert['status'] }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $alert['status'] }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-4 text-gray-700">
                                {{ $alert['message'] }}
                            </p>

                            <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Market Price
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-red-600">
                                        ৳{{ number_format($alert['market_price_paisa'] / 100, 2) }}/kg
                                    </p>
                                </div>

                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Wholesale Price
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-green-700">
                                        ৳{{ number_format($alert['wholesale_price_paisa'] / 100, 2) }}/kg
                                    </p>
                                </div>

                                <div class="rounded-md bg-gray-50 p-4">
                                    <p class="text-sm text-gray-500">
                                        Possible Savings
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-indigo-700">
                                        {{ number_format($alert['savings_percentage'], 1) }}%
                                    </p>
                                </div>
                            </div>

                            @if(auth()->user()->isNeighbor())
                                <div class="mt-5">
                                    <a
                                        href="{{ route('group-carts.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                                    >
                                        Create Group Cart
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>