<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'vendor-approved')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Vendor approved successfully.
                </div>
            @endif

            @if (session('status') === 'vendor-marked-pending')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md">
                    Vendor marked as pending successfully.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Welcome, {{ $user->name }}
                        </h3>

                        <p class="mt-2 text-gray-600">
                            You are logged in as an admin. From here, you can approve vendors, manage disputes, and monitor overall system health.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ route('admin.vendors.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Manage Vendors
                        </a>

                        <a
                            href="{{ route('admin.disputes.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800"
                        >
                            Manage Disputes
                        </a>

                        <a
                            href="{{ route('admin.system-health.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800"
                        >
                            System Health
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Total Users
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-indigo-700">
                        {{ $totalUsers }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Neighbors
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        {{ $totalNeighbors }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Vendors
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-orange-600">
                        {{ $totalVendors }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Pending Vendor Approvals
                    </h4>

                    <p class="mt-3 text-3xl font-bold text-red-600">
                        {{ $pendingVendors }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Vendor Approval
                    </h4>

                    <p class="mt-3 text-gray-600">
                        Review vendor profiles and verify trade license information.
                    </p>

                    <a
                        href="{{ route('admin.vendors.index') }}"
                        class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-900 underline"
                    >
                        Open vendor approval
                    </a>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        Dispute Resolution
                    </h4>

                    <p class="mt-3 text-gray-600">
                        Review neighbor disputes, requested refunds, and admin decisions.
                    </p>

                    <a
                        href="{{ route('admin.disputes.index') }}"
                        class="inline-block mt-4 text-sm text-red-600 hover:text-red-900 underline"
                    >
                        Open dispute dashboard
                    </a>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">
                        System Health Tracker
                    </h4>

                    <p class="mt-3 text-gray-600">
                        Monitor carts, orders, escrow, vendor payments, ratings, substitutions, and disputes.
                    </p>

                    <a
                        href="{{ route('admin.system-health.index') }}"
                        class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-900 underline"
                    >
                        Open system health tracker
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>