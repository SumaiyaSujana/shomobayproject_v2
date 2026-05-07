<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vendor Dashboard
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
                        You are logged in as a vendor, farmer, or wholesaler. After admin verification, you will be able to bid on neighborhood bulk orders.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Verification Status</h4>

                    @if($vendorProfile?->is_verified)
                        <p class="mt-3 text-lg font-bold text-green-700">
                            Verified
                        </p>
                    @else
                        <p class="mt-3 text-lg font-bold text-red-600">
                            Pending Approval
                        </p>
                    @endif
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Wallet Balance</h4>
                    <p class="mt-3 text-3xl font-bold text-green-700">
                        ৳{{ number_format(($wallet?->balance_paisa ?? 0) / 100, 2) }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Business Name</h4>
                    <p class="mt-3 text-lg text-gray-700">
                        {{ $vendorProfile?->business_name ?? 'Not added yet' }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Sprint 3 Preview
                </h4>

                <p class="mt-2 text-gray-600">
                    Later, we will build the bidding system where verified vendors can place bids on group carts that reach the wholesale threshold.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>