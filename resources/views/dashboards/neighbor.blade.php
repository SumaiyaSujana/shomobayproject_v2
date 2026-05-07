<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Neighbor Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'neighbor-profile-updated')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Neighbor profile updated successfully.
                </div>
            @endif

            @if (session('status') === 'wallet-topped-up')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Demo wallet balance added successfully.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Welcome, {{ $user->name }}
                        </h3>

                        <p class="mt-2 text-gray-600">
                            You are logged in as a neighbor. From here, you can join group carts, add grocery contributions, track escrow payments, and claim delivered items.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ route('group-carts.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                        >
                            Browse Group Carts
                        </a>

                        <a
                            href="{{ route('group-carts.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800"
                        >
                            Create Group Cart
                        </a>

                        <a
                            href="{{ route('claim-tokens.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800"
                        >
                            My Claim Tokens
                        </a>

                        <a
                            href="{{ route('neighbor.profile.edit') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Edit Neighbor Profile
                        </a>
                    </div>
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

                    <p class="mt-2 text-sm text-gray-500">
                        Coordinates:
                        {{ $neighborProfile?->location_coordinates ?? 'Not added yet' }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Add Demo Wallet Balance
                </h4>

                <p class="mt-2 text-gray-600">
                    This is a local testing wallet top-up. In a real system, this would be replaced by a payment gateway.
                </p>

                <form method="POST" action="{{ route('wallet.top-up') }}" class="mt-6 flex flex-col sm:flex-row gap-4">
                    @csrf

                    <div class="flex-1">
                        <x-input-label for="amount_taka" value="Amount in Taka" />

                        <x-text-input
                            id="amount_taka"
                            name="amount_taka"
                            type="number"
                            step="0.01"
                            min="10"
                            class="mt-1 block w-full"
                            placeholder="Example: 5000"
                            required
                        />

                        <x-input-error class="mt-2" :messages="$errors->get('amount_taka')" />
                    </div>

                    <div class="flex items-end">
                        <x-primary-button>
                            Add Balance
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Sprint 4 Progress
                </h4>

                <p class="mt-2 text-gray-600">
                    Digital claim tokens are now available after a vendor bid is accepted. Neighbors can use tokens to claim their delivery share.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>