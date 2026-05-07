<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vendor Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'vendor-profile-updated')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Vendor profile updated successfully.
                </div>
            @endif

            @if (session('status') === 'vendor-not-verified')
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                    Your vendor profile must be approved by admin before you can bid on bulk requests.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Welcome, {{ $user->name }}
                        </h3>

                        <p class="mt-2 text-gray-600">
                            You are logged in as a vendor, farmer, or wholesaler. After admin verification, you can bid on neighborhood bulk orders and track your revenue analytics.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ route('vendor.bulk-requests.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                        >
                            Browse Bulk Requests
                        </a>

                        <a
                            href="{{ route('vendor.analytics.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800"
                        >
                            Revenue Analytics
                        </a>

                        <a
                            href="{{ route('vendor.profile.edit') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Edit Vendor Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                    <h4 class="font-semibold text-gray-900">Available Requests</h4>

                    <p class="mt-3 text-3xl font-bold text-indigo-700">
                        {{ $availableBulkRequests }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">My Bids</h4>

                    <p class="mt-3 text-3xl font-bold text-orange-600">
                        {{ $activeVendorBids }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Wallet Balance</h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        ৳{{ number_format(($wallet?->balance_paisa ?? 0) / 100, 2) }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Business Profile</h4>

                    <p class="mt-3 text-lg text-gray-700">
                        {{ $vendorProfile?->business_name ?? 'Not added yet' }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        License File:
                        {{ $vendorProfile?->trade_license_file ? 'Uploaded' : 'Not uploaded yet' }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Revenue Analytics</h4>

                    <p class="mt-3 text-gray-600">
                        View expected earnings, accepted orders, bid performance, and most requested items.
                    </p>

                    <a
                        href="{{ route('vendor.analytics.index') }}"
                        class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-900 underline"
                    >
                        Open revenue analytics
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>