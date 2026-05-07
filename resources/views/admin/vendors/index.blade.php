<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vendor Verification Requests
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
                    Vendor marked as pending again.
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Vendor Verification Center
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Review vendor, farmer, and wholesaler profiles before allowing them to bid on neighborhood bulk orders.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">

                    @if($vendors->isEmpty())
                        <p class="text-gray-600">
                            No vendor profiles found yet.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Vendor Name
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Email
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Business Name
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            License File
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status
                                        </th>

                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Action
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach($vendors as $vendor)
                                        <tr>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                {{ $vendor->user?->name ?? 'Unknown User' }}
                                            </td>

                                            <td class="px-4 py-4 text-sm text-gray-600">
                                                {{ $vendor->user?->email ?? 'No email' }}
                                            </td>

                                            <td class="px-4 py-4 text-sm text-gray-600">
                                                {{ $vendor->business_name ?? 'Not added yet' }}
                                            </td>

                                            <td class="px-4 py-4 text-sm">
                                                @if($vendor->trade_license_file)
                                                    <a
                                                        href="{{ asset('storage/' . $vendor->trade_license_file) }}"
                                                        target="_blank"
                                                        class="text-indigo-600 hover:text-indigo-900 underline"
                                                    >
                                                        View File
                                                    </a>
                                                @else
                                                    <span class="text-gray-500">
                                                        Not uploaded
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 text-sm">
                                                @if($vendor->is_verified)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Verified
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 text-sm text-right">
                                                @if($vendor->is_verified)
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.vendors.mark-pending', $vendor) }}"
                                                        class="inline"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center px-3 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700"
                                                        >
                                                            Mark Pending
                                                        </button>
                                                    </form>
                                                @else
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.vendors.approve', $vendor) }}"
                                                        class="inline"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center px-3 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                                                        >
                                                            Approve
                                                        </button>
                                                    </form>
                                                @endif
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
    </div>
</x-app-layout>