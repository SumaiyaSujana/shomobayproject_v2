<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Neighbor Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900">
                        Welcome, {{ $user->name }}
                    </h3>

                    <p class="mt-2 text-gray-600">
                        You are logged in as a neighbor. From here, you will join group carts, add grocery contributions, track escrow payments, and claim delivered items.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Wallet Balance</h4>
                    <p class="mt-3 text-3xl font-bold text-green-700">
                        ৳{{ number_format(($wallet?->balance_paisa ?? 0) / 100, 2) }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Escrow Amount</h4>
                    <p class="mt-3 text-3xl font-bold text-orange-600">
                        ৳{{ number_format(($wallet?->escrow_paisa ?? 0) / 100, 2) }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Apartment Building</h4>
                    <p class="mt-3 text-lg text-gray-700">
                        {{ $neighborProfile?->apartment_building ?? 'Not added yet' }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Sprint 2 Preview
                </h4>

                <p class="mt-2 text-gray-600">
                    Next, we will build the Neighborhood Group Cart Engine. This will allow neighbors to create shared grocery carts and contribute item quantities.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>