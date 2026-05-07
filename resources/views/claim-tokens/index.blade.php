<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Digital Claim Tokens
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Delivery Claim Tokens
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Use these tokens to claim your share from the delivery truck or delivery coordinator.
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

            @if($contributions->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        You do not have any claim tokens yet. Tokens are created after a vendor bid is accepted for a group cart you joined.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($contributions as $contribution)
                        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">
                                        {{ $contribution->groupCart?->title ?? 'Unknown Cart' }}
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Item:
                                        {{ $contribution->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Quantity:
                                        {{ number_format($contribution->quantity_grams / 1000, 2) }} kg
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Vendor:
                                        {{ $contribution->groupCart?->order?->vendor?->vendorProfile?->business_name ?? $contribution->groupCart?->order?->vendor?->name ?? 'Not assigned' }}
                                    </p>
                                </div>

                                @if($contribution->claimed_at)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Claimed
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Unclaimed
                                    </span>
                                @endif
                            </div>

                            <div class="mt-5 rounded-md bg-gray-50 p-4">
                                <p class="text-sm text-gray-500">
                                    Claim Token
                                </p>

                                <p class="mt-1 text-2xl font-bold tracking-widest text-indigo-700">
                                    {{ $contribution->qr_claim_token }}
                                </p>
                            </div>

                            <div class="mt-5">
                                <a
                                    href="{{ route('claim-tokens.show', $contribution) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                >
                                    Open Token
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>