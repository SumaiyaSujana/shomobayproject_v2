<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
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
                        You are logged in as an admin. You will manage vendor approvals, disputes, platform health, and system monitoring.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Vendor Approval</h4>
                    <p class="mt-3 text-gray-600">
                        Coming in Sprint 3
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Dispute Management</h4>
                    <p class="mt-3 text-gray-600">
                        Coming in Sprint 4
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">System Health</h4>
                    <p class="mt-3 text-gray-600">
                        Coming in Sprint 4
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>